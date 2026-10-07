@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 flex items-end justify-between border-b border-[#E5E3DB] pb-5">
        <div>
            <a href="{{ route('tasks.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Daftar Tugas</a>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">{{ $task->title }}</h1>
        </div>
        @if ($submissions->isEmpty())
            <a href="{{ route('tasks.edit', $task) }}"
                class="bg-[#16213A] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Ubah</a>
        @endif
    </div>

    @if ($submissions->isNotEmpty())
        <div class="mb-6"><x-alert type="INFO">Sudah ada dealer yang mengumpulkan, sehingga tugas ini tidak dapat diubah atau dihapus.</x-alert></div>
    @endif

    <dl class="mb-10 grid gap-6 border border-[#E5E3DB] bg-white p-8 sm:grid-cols-2">
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Departemen</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->department->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Area</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->area->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Batas Waktu</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->due_at->format('d M Y') }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Dibuat Oleh</dt>
            <dd class="mt-1 text-[#16213A]">{{ $task->creator->name }}</dd>
        </div>
    </dl>

    <h2 class="font-display mb-4 text-xl font-semibold text-[#16213A]">Pengumpulan Dealer</h2>
    <div class="border border-[#E5E3DB] bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#16213A] text-[11px] uppercase tracking-[0.15em] text-[#16213A]">
                    <th class="px-5 py-3.5 font-semibold">Dealer</th>
                    <th class="px-5 py-3.5 font-semibold">Tanggal Kumpul</th>
                    <th class="px-5 py-3.5 font-semibold">Status</th>
                    <th class="px-5 py-3.5 text-right font-semibold">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr class="border-b border-[#EFEDE6]">
                        <td class="px-5 py-4 font-medium text-[#16213A]">{{ $submission->dealer->name }}</td>
                        <td class="px-5 py-4">{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4"><x-status-badge :status="$submission->status" /></td>
                        <td class="px-5 py-4 text-right text-xs font-medium">
                            <a href="{{ route('reviews.show', $submission) }}" class="text-[#16213A] hover:text-[#A16207]">Periksa</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-slate-500">Belum ada dealer yang mengumpulkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
