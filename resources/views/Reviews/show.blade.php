@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <a href="{{ route('reviews.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Pemeriksaan Tugas</a>
        <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Periksa Tugas</h1>
    </div>

    <dl class="mb-8 grid gap-6 border border-[#E5E3DB] bg-white p-8 sm:grid-cols-2">
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Nama Laporan</dt>
            <dd class="mt-1 font-medium text-[#16213A]">{{ $submission->task->title }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Nama Dealer</dt>
            <dd class="mt-1 text-[#16213A]">{{ $submission->dealer->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Tanggal Kumpul</dt>
            <dd class="mt-1 text-[#16213A]">{{ $submission->submitted_at->format('d M Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Status Saat Ini</dt>
            <dd class="mt-1"><x-status-badge :status="$submission->status" /></dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Link Google Drive</dt>
            <dd class="mt-1 break-all"><a href="{{ $submission->drive_link }}" target="_blank" rel="noopener noreferrer" class="text-[#16213A] underline hover:text-[#A16207]">{{ $submission->drive_link }}</a></dd>
        </div>
        @if ($submission->note)
            <div class="sm:col-span-2">
                <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Catatan Dealer</dt>
                <dd class="mt-1">{{ $submission->note }}</dd>
            </div>
        @endif
    </dl>

    <form action="{{ route('reviews.update', $submission) }}" method="POST" class="mb-10 space-y-6 border border-[#E5E3DB] bg-white p-8">
        @csrf
        @method('PUT')
        <div>
            <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Status Pengumpulan</label>
            <select id="status" name="status"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                <option value="">Pilih status</option>
                @foreach ($options as $option)
                    <option value="{{ $option->value }}" @selected(old('status', $submission->status->value) === $option->value)>{{ $option->value }}</option>
                @endforeach
            </select>
            @error('status') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="note" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Catatan</label>
            <textarea id="note" name="note" rows="4"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">{{ old('note') }}</textarea>
            @error('note') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end border-t border-[#EFEDE6] pt-6">
            <button type="submit" class="cursor-pointer bg-[#16213A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Simpan Hasil Pemeriksaan</button>
        </div>
    </form>

    <h2 class="font-display mb-4 text-xl font-semibold text-[#16213A]">Log Aktivitas</h2>
    <x-log-table :logs="$logs" />
@endsection
