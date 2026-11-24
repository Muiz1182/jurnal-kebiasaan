<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru');

        // DIREVISI: Ambil daftar mata pelajaran unik (berdasarkan kolom jabatan) untuk filter dinamis
        $subjects = User::where('role', 'guru')
                        ->whereNotNull('jabatan')
                        ->where('jabatan', '!=', '')
                        ->distinct()
                        ->pluck('jabatan');

        // Fitur Pencarian (Nama, NIP, atau Username)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Mata Pelajaran / Jabatan
        if ($request->has('subject') && $request->subject != '') {
            $query->where('jabatan', $request->subject);
        }

        // Pagination (10 data per halaman)
        $teachers = $query->paginate(10)->withQueryString();

        // DIREVISI: Kirim variabel $subjects ke view
        return view('admin.teachers.index', compact('teachers', 'subjects'));
    }

    /**
     * Menampilkan form tambah guru baru.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Menyimpan data guru baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nip' => 'nullable|string|unique:users,nip',
            'jabatan' => 'nullable|string|max:100',
            'kelas_mengajar' => 'nullable|string|max:20',
            'password' => 'required|min:6',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'kelas_mengajar' => $request->kelas_mengajar,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru dan akun berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit guru.
     */
    public function edit(User $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Memperbarui data guru di database.
     */
    public function update(Request $request, User $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $teacher->id,
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'nip' => 'nullable|string|unique:users,nip,' . $teacher->id,
            'jabatan' => 'nullable|string|max:100',
            'kelas_mengajar' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $teacher->name = $request->name;
        $teacher->username = $request->username;
        $teacher->email = $request->email;
        $teacher->nip = $request->nip;
        $teacher->jabatan = $request->jabatan;
        $teacher->kelas_mengajar = $request->kelas_mengajar;
        
        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Menghapus data guru dari database.
     */
    public function destroy(User $teacher)
    {
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }
}