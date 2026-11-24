@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('dashboard') }}" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-blue-900">Hasil Pencarian</h1>
                <p class="text-gray-500 text-sm">Menampilkan hasil untuk: <span class="font-bold italic">"{{ $query }}"</span></p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl border shadow-sm">
            @if(isset($results) && count($results) > 0)
                <ul class="space-y-4">
                    @foreach($results as $item)
                        <li class="p-4 border-b last:border-0 hover:bg-gray-50 transition rounded-lg">
                            <a href="#" class="block">
                                <h3 class="font-bold text-blue-900">{{ $item->title ?? 'Data Ditemukan' }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->description ?? 'Deskripsi singkat item terkait...' }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="text-gray-500 font-medium">Maaf, tidak ada data yang cocok dengan "{{ $query }}".</p>
                    <a href="{{ route('dashboard') }}" class="text-blue-900 font-bold underline mt-4 block">Kembali ke Dashboard</a>
                </div>
            @endif
        </div>
    </div>
@endsection