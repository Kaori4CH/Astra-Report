@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <a href="{{ route('submissions.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Tugas Saya</a>
        <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Pengumpulan Tugas</h1>
    </div>

    <dl class="mb-8 grid gap-6 border border-[#E5E3DB] bg-white p-8 sm:grid-cols-2">
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Nama Laporan</dt>
            <dd class="mt-1 font-medium text-[#16213A]">{{ $task->title }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Deadline</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->due_at->format('d M Y') }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Departemen</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->department->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Area</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->area->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Status Pengumpulan</dt>
            <dd class="mt-1"><x-status-badge :status="$submission?->status" /></dd>
        </div>
        @if ($submission)
            <div>
                <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Link Terakhir</dt>
                <dd class="mt-1 break-all"><a href="{{ $submission->drive_link }}" target="_blank" rel="noopener noreferrer" class="text-[#16213A] underline hover:text-[#A16207]">{{ $submission->drive_link }}</a></dd>
            </div>
        @endif
    </dl>

    @if ($blockedReason)
        <div class="mb-8"><x-alert type="WARNING">{{ $blockedReason }}</x-alert></div>
    @else
        @if ($submission)
            <div class="mb-6"><x-alert type="INFO">Supervisor meminta revisi. Silakan kumpulkan ulang.</x-alert></div>
        @endif
        <form action="{{ route('submissions.store', $task) }}" method="POST" class="mb-10 space-y-6 border border-[#E5E3DB] bg-white p-8">
            @csrf
            <div>
                <label for="drive_link" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Link Google Drive</label>
                <input type="text" id="drive_link" name="drive_link" value="{{ old('drive_link') }}" placeholder="https://drive.google.com/..."
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
                @error('drive_link') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="note" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Catatan (Opsional)</label>
                <textarea id="note" name="note" rows="4"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">{{ old('note') }}</textarea>
                @error('note') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end border-t border-[#EFEDE6] pt-6">
                <button type="submit" class="cursor-pointer bg-[#16213A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">
                    {{ $submission ? 'Kumpulkan Ulang' : 'Kumpulkan' }}
                </button>
            </div>
        </form>
    @endif

    <h2 class="font-display mb-4 text-xl font-semibold text-[#16213A]">Log Aktivitas</h2>
    <x-log-table :logs="$logs" />
@endsection
