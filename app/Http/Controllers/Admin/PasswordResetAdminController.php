<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;

class PasswordResetAdminController extends Controller
{
    /**
     * Tampilkan daftar pengajuan reset sandi untuk verifikasi admin.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = PasswordResetRequest::with(['user', 'approver'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected', 'completed'])) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('identifier', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%")
                         ->orWhere('kelas', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        // Hitung statistik untuk badge tab
        $counts = [
            'all' => PasswordResetRequest::count(),
            'pending' => PasswordResetRequest::where('status', 'pending')->count(),
            'approved' => PasswordResetRequest::where('status', 'approved')->count(),
            'rejected' => PasswordResetRequest::where('status', 'rejected')->count(),
            'completed' => PasswordResetRequest::where('status', 'completed')->count(),
        ];

        return view('admin.password-resets.index', compact('requests', 'status', 'search', 'counts'));
    }

    /**
     * Setujui permohonan reset sandi murid.
     */
    public function approve(Request $request, $id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        if ($resetRequest->isCompleted()) {
            return back()->with('warning', 'Permintaan ini sudah selesai digunakan dan tidak dapat disetujui ulang.');
        }

        $resetRequest->status = 'approved';
        $resetRequest->approved_by = auth()->id();
        $resetRequest->approved_at = now();
        $resetRequest->expires_at = now()->addHours(24); // Berikan waktu 1x24 jam sejak disetujui
        if ($request->filled('admin_notes')) {
            $resetRequest->admin_notes = $request->admin_notes;
        }
        $resetRequest->save();

        return back()->with('success', "Permintaan reset sandi untuk {$resetRequest->user->name} berhasil disetujui. Murid dapat langsung mengubah kata sandi.");
    }

    /**
     * Tolak permohonan reset sandi murid.
     */
    public function reject(Request $request, $id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        if ($resetRequest->isCompleted()) {
            return back()->with('warning', 'Permintaan ini sudah selesai digunakan.');
        }

        $resetRequest->status = 'rejected';
        $resetRequest->admin_notes = $request->admin_notes ?: 'Permintaan reset sandi ditolak oleh Admin.';
        $resetRequest->save();

        return back()->with('success', "Permintaan reset sandi untuk {$resetRequest->user->name} telah ditolak.");
    }
}
