<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    // REVISI: Method __construct yang memanggil $this->middleware() dihapus 
    // karena sudah tidak didukung pada versi Laravel terbaru.

    public function index()
    {
        // Pengamanan tambahan: Pastikan user sudah login & role user adalah admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // REVISI: Hanya menghitung user yang berperan sebagai guru, bukan keseluruhan tabel users (admin + guru)
        $totalTeachers = User::where('role', 'guru')->count();
        $totalJournals = Journal::count();

        return view('admin.dashboard', compact('totalTeachers', 'totalJournals'));
    }
}