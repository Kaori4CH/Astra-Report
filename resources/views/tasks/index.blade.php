@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 flex items-end justify-between border-b border-[#E5E3DB] pb-5">
        <div>
            <p class="mb-1 text-[11px] uppercase tracking-[0.2em] text-[#A16207]">Manajemen Tugas</p>
            <h1 class="font-display text-3xl font-semibold text-[#16213A]">Daftar Tugas</h1>
        </div>
        <a href="{{ route('tasks.create') }}"
            class="bg-[#16213A] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Tambah Tugas</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 flex flex-wrap items-end gap-3 border border-[#E5E3DB] bg-white p-5">
        <div class="min-w-55 flex-1">
            <label for="search" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Cari</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama laporan"
                class="w-full border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
        </div>
        <div>
            <label for="department_id" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Departemen</label>
            <select name="department_id" id="department_id" class="border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
                <option value="">Semua</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected((string) request('department_id') === (string) $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="area_id" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Area</label>
            <select name="area_id" id="area_id" class="border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
                <option value="">Semua</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected((string) request('area_id') === (string) $area->id)>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-[#16213A] px-5 py-2 text-sm font-medium text-white transition hover:bg-[#26324f]">Terapkan</button>
    </form>

    <div class="overflow-x-auto border border-[#E5E3DB] bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#16213A] text-[11px] uppercase tracking-[0.15em] text-[#16213A]">
                    <th class="w-14 px-5 py-3.5 font-semibold">No.</th>
                    <th class="px-5 py-3.5 font-semibold">Nama Laporan</th>
                    <th class="px-5 py-3.5 font-semibold">Departemen</th>
                    <th class="px-5 py-3.5 font-semibold">Area</th>
                    <th class="px-5 py-3.5 font-semibold">Batas Waktu</th>
                    <th class="px-5 py-3.5 font-semibold">Dikumpulkan</th>
                    <th class="px-5 py-3.5 text-right font-semibold">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr class="border-b border-[#EFEDE6] hover:bg-[#FAF9F5]">
                        <td class="px-5 py-4 font-display text-lg text-[#A16207]">{{ $tasks->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4 font-medium text-[#16213A]">{{ $task->title }}</td>
                        <td class="px-5 py-4">{{ $task->department->name }}</td>
                        <td class="px-5 py-4">{{ $task->area->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $task->due_at->format('d M Y') }}</td>
                        <td class="px-5 py-4">{{ $task->submissions_count }}</td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-4 text-xs font-medium">
                                <a href="{{ route('tasks.show', $task) }}" class="text-[#16213A] hover:text-[#A16207]">Lihat</a>
                                @if ($task->submissions_count === 0)
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-[#16213A] hover:text-[#A16207]">Ubah</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer text-red-700 hover:text-red-900">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-slate-400" title="Sudah ada dealer yang mengumpulkan">Terkunci</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-slate-500">Belum ada tugas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $tasks->links() }}</div>
@endsection
