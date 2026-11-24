@extends('layouts.admin')

@section('title', 'Verifikasi Reset Sandi Murid')

@section('content')
<div class="w-full px-6 py-6 space-y-6" x-data="{ 
    approveModal: false, 
    rejectModal: false,
    selectedId: null,
    selectedName: '',
    adminNotes: '',
    copyToast: false,
    openApprove(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.adminNotes = '';
        this.approveModal = true;
    },
    openReject(id, name) {
        this.selectedId = id;
        this.selectedName = name;
        this.adminNotes = '';
        this.rejectModal = true;
    },
    copyLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            this.copyToast = true;
            setTimeout(() => { this.copyToast = false; }, 3000);
        });
    }
}">

    <!-- Toast Copy Feedback -->
    <div x-show="copyToast" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-6 right-6 z-50 bg-emerald-700 text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-bold border border-emerald-500">
        <span>📋</span> Tautan reset sandi berhasil disalin ke clipboard!
    </div>
    
    <!-- Header & Breadcrumb -->
    <div>
        <nav class="text-xs text-gray-400 font-medium mb-1">
            Portal <span class="mx-1">&gt;</span> <span class="text-gray-600 font-bold">Verifikasi Reset Sandi</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Verifikasi Reset Sandi Murid</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Kelola dan verifikasi permohonan lupa sandi akun murid dengan masa aktif 1x24 jam.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl text-xs font-bold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $counts['pending'] }} Menunggu Persetujuan
                </span>
            </div>
        </div>
    </div>

    <!-- Notifikasi Flash Message -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center gap-3">
            <span class="text-lg">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('warning'))
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-sm font-semibold flex items-center gap-3">
            <span class="text-lg">⚡</span>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <!-- Filter Tab Status & Search Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <!-- Tab Status -->
        <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 pb-4">
            <a href="{{ route('admin.password-resets.index', ['status' => 'all', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'all' ? 'bg-blue-900 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.password-resets.index', ['status' => 'pending', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                <span>⏳</span> Menunggu ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.password-resets.index', ['status' => 'approved', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                <span>✅</span> Disetujui ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.password-resets.index', ['status' => 'rejected', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">
                <span>❌</span> Ditolak ({{ $counts['rejected'] }})
            </a>
            <a href="{{ route('admin.password-resets.index', ['status' => 'completed', 'search' => request('search')]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $status === 'completed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100 border border-indigo-200' }}">
                <span>🎉</span> Selesai ({{ $counts['completed'] }})
            </a>
        </div>

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('admin.password-resets.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari berdasarkan nama murid, username, NISN, atau kelas..." 
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-900/20 focus:border-blue-900 transition">
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm rounded-2xl shadow-sm transition">
                Cari Data
            </button>
            @if ($search)
                <a href="{{ route('admin.password-resets.index', ['status' => $status]) }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-2xl transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Permohonan -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto min-h-[300px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50/50 text-blue-900 text-xs font-black uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Data Murid</th>
                        <th class="py-4 px-6">Alasan Permohonan</th>
                        <th class="py-4 px-6">Waktu &amp; Masa Berlaku</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Verifikator &amp; Catatan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($requests as $item)
                        @php
                            $isExpired = $item->isExpired();
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Data Murid -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-900 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-sm">
                                        {{ strtoupper(substr($item->user->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-gray-900 truncate">
                                            {{ $item->user->name ?? 'Murid Tidak Dikenal' }}
                                        </div>
                                        <div class="text-xs text-gray-500 font-medium">
                                            {{ $item->user->username ? '@'.$item->user->username : $item->user->email }}
                                            @if ($item->user->kelas)
                                                &bull; Kelas {{ $item->user->kelas }}
                                            @endif
                                            @if ($item->user->nisn)
                                                &bull; NISN: {{ $item->user->nisn }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Alasan Permohonan -->
                            <td class="py-4 px-6">
                                <p class="text-xs text-gray-700 max-w-xs line-clamp-2">
                                    {{ $item->reason ?: 'Tidak mencantumkan keterangan khusus' }}
                                </p>
                            </td>

                            <!-- Waktu & Masa Berlaku -->
                            <td class="py-4 px-6">
                                <div class="text-xs">
                                    <div class="font-bold text-gray-800">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        {{ $item->created_at->diffForHumans() }}
                                    </div>
                                    @if ($item->expires_at)
                                        <div class="mt-1 text-[11px] {{ $isExpired ? 'text-rose-600 font-bold' : 'text-amber-700 font-semibold' }}">
                                            {{ $isExpired ? 'Sudah Kedaluwarsa' : 'Aktif s/d ' . $item->expires_at->translatedFormat('d M, H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-6">
                                @if ($item->status === 'pending')
                                    @if ($isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            <span>⌛</span> Kedaluwarsa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm animate-pulse">
                                            <span>⏳</span> Menunggu Verifikasi
                                        </span>
                                    @endif
                                @elseif ($item->status === 'approved')
                                    @if ($isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            <span>⚠️</span> Disetujui (Kedaluwarsa)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span>✅</span> Disetujui (Siap Ganti)
                                        </span>
                                    @endif
                                @elseif ($item->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                        <span>❌</span> Ditolak
                                    </span>
                                @elseif ($item->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        <span>🎉</span> Sandi Telah Diubah
                                    </span>
                                @endif
                            </td>

                            <!-- Verifikator & Catatan -->
                            <td class="py-4 px-6">
                                <div class="text-xs">
                                    @if ($item->approver)
                                        <div class="font-bold text-gray-800">
                                            Oleh: {{ $item->approver->name }}
                                        </div>
                                    @endif
                                    @if ($item->admin_notes)
                                        <div class="text-gray-600 italic mt-0.5">
                                            "{{ $item->admin_notes }}"
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Jika Pending --}}
                                    @if ($item->status === 'pending')
                                        <button type="button" 
                                                @click="openApprove({{ $item->id }}, '{{ addslashes($item->user->name ?? 'Murid') }}')"
                                                class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                                            <span>✅</span> Setujui
                                        </button>
                                        <button type="button" 
                                                @click="openReject({{ $item->id }}, '{{ addslashes($item->user->name ?? 'Murid') }}')"
                                                class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                            <span>❌</span> Tolak
                                        </button>
                                    @endif

                                    {{-- Jika Approved --}}
                                    @if ($item->status === 'approved')
                                        <button type="button" 
                                                @click="copyLink('{{ route('password.reset.form', ['token' => $item->token]) }}')"
                                                class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-900 border border-blue-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                                                title="Salin tautan reset sandi untuk diberikan ke murid">
                                            <span>📋</span> Salin Link Ganti Sandi
                                        </button>
                                    @endif

                                    {{-- Detail Status Link --}}
                                    <a href="{{ route('password.reset.status', ['token' => $item->token]) }}" 
                                       target="_blank" 
                                       class="p-2 text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition" 
                                       title="Buka halaman status murid">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <div class="text-4xl mb-2">📭</div>
                                <div class="font-bold text-gray-600">Tidak ada data permohonan reset sandi</div>
                                <div class="text-xs text-gray-400 mt-1">Gunakan filter status lain atau ubah kata kunci pencarian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($requests->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL PERSETUJUAN (APPROVE) -->
    <div x-show="approveModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-slate-900 shadow-2xl border border-slate-100" @click.away="approveModal = false">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl mb-4 border border-emerald-200">
                ✅
            </div>
            <h3 class="text-lg font-black text-slate-900">Setujui Reset Kata Sandi?</h3>
            <p class="text-xs text-slate-500 mt-1">
                Anda akan menyetujui permohonan reset sandi untuk murid <span class="font-bold text-slate-800" x-text="selectedName"></span>. Masa aktif ganti sandi 1x24 jam akan langsung dimulai.
            </p>

            <form :action="'{{ url('/admin/password-resets') }}/' + selectedId + '/approve'" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Catatan Admin untuk Murid (Opsional)</label>
                    <input type="text" 
                           name="admin_notes" 
                           placeholder="Contoh: Disetujui, segera ganti password di perangkat pribadi." 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="approveModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition cursor-pointer">
                        Ya, Setujui Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PENOLAKAN (REJECT) -->
    <div x-show="rejectModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-slate-900 shadow-2xl border border-slate-100" @click.away="rejectModal = false">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center text-2xl mb-4 border border-rose-200">
                ❌
            </div>
            <h3 class="text-lg font-black text-slate-900">Tolak Permohonan Reset Sandi?</h3>
            <p class="text-xs text-slate-500 mt-1">
                Permohonan untuk <span class="font-bold text-slate-800" x-text="selectedName"></span> akan ditolak dan murid tidak akan dapat mengganti sandi melalui pengajuan ini.
            </p>

            <form :action="'{{ url('/admin/password-resets') }}/' + selectedId + '/reject'" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Alasan Penolakan</label>
                    <input type="text" 
                           name="admin_notes" 
                           required
                           placeholder="Contoh: Identitas belum terkonfirmasi oleh wali kelas." 
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-600">
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="rejectModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs rounded-xl shadow-md transition cursor-pointer">
                        Tolak Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
