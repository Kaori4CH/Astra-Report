@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <p class="mb-1 text-[11px] uppercase tracking-[0.2em] text-[#A16207]">Pemeriksaan Tugas</p>
        <h1 class="font-display text-3xl font-semibold text-[#16213A]">Hasil Pengumpulan Dealer</h1>
    </div>

    <form method="GET" action="{{ route('reviews.index') }}" class="mb-6 flex flex-wrap items-end gap-3 border border-[#E5E3DB] bg-white p-5">
        <div class="min-w-55 flex-1">
            <label for="search" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Cari</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama laporan atau dealer"
                class="w-full border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
        </div>
        <div>
            <label for="status" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Status</label>
            <select name="status" id="status" class="border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
                <option value="">Semua Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
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
                    <th class="px-5 py-3.5 font-semibold">Nama Dealer</th>
                    <th class="px-5 py-3.5 font-semibold">Tanggal Kumpul</th>
                    <th class="px-5 py-3.5 font-semibold">Status</th>
                    <th class="px-5 py-3.5 text-right font-semibold">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)
                    <tr class="border-b border-[#EFEDE6] hover:bg-[#FAF9F5]">
                        <td class="px-5 py-4 font-display text-lg text-[#A16207]">{{ $submissions->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4 font-medium text-[#16213A]">{{ $submission->task->title }}</td>
                        <td class="px-5 py-4">{{ $submission->dealer->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $submission->submitted_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4"><x-status-badge :status="$submission->status" /></td>
                        <td class="px-5 py-4 text-right text-xs font-medium">
                            <a href="{{ route('reviews.show', $submission) }}" class="text-[#16213A] hover:text-[#A16207]">Periksa</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-slate-500">Belum ada tugas yang dikumpulkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $submissions->links() }}</div>
@endsection
