@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <a href="{{ route('tasks.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Daftar Tugas</a>
        <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Tambah Tugas Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Tugas akan tampil untuk semua dealer.</p>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6 border border-[#E5E3DB] bg-white p-8">
        @csrf
        
        <div>
            <label for="title" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Nama Laporan</label>
            <input type="text" id="title" name="title" value="{{ old('title', '') }}" placeholder="Contoh: Laporan Penjualan Bulanan"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
            @error('title') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="department_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Departemen</label>
                <select id="department_id" name="department_id" class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
                    <option value="">Pilih departemen</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected((string) old('department_id', '') === (string) $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="area_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Area</label>
                <select id="area_id" name="area_id" class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
                    <option value="">Pilih area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected((string) old('area_id', '') === (string) $area->id)>{{ $area->name }}</option>
                    @endforeach
                </select>
                @error('area_id') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="due_at" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Batas Waktu</label>
            <input type="date" id="due_at" name="due_at" value="{{ old('due_at', now()->toDateString()) }}" class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
            @error('due_at') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4 border-t border-[#EFEDE6] pt-6">
            <a href="{{ route('tasks.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-[#16213A]">Batal</a>
            <button type="submit" class="cursor-pointer bg-[#16213A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Simpan</button>
        </div>
    </form>
@endsection
