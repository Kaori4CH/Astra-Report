@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <p class="mb-1 text-[11px] uppercase tracking-[0.2em] text-[#A16207]">Pengumpulan Tugas</p>
        <h1 class="font-display text-3xl font-semibold text-[#16213A]">Tugas Saya</h1>
    </div>

    <div class="overflow-x-auto border border-[#E5E3DB] bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#16213A] text-[11px] uppercase tracking-[0.15em] text-[#16213A]">
                    <th class="w-14 px-5 py-3.5 font-semibold">No.</th>
                    <th class="px-5 py-3.5 font-semibold">Nama Laporan</th>
                    <th class="px-5 py-3.5 font-semibold">Departemen</th>
                    <th class="px-5 py-3.5 font-semibold">Area</th>
                    <th class="px-5 py-3.5 font-semibold">Deadline</th>
                    <th class="px-5 py-3.5 font-semibold">Status</th>
                    <th class="px-5 py-3.5 text-right font-semibold">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    @php $submission = $submissions->get($task->id); @endphp
                    <tr class="border-b border-[#EFEDE6] hover:bg-[#FAF9F5]">
                        <td class="px-5 py-4 font-display text-lg text-[#A16207]">{{ $tasks->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4 font-medium text-[#16213A]">{{ $task->title }}</td>
                        <td class="px-5 py-4">{{ $task->department->name }}</td>
                        <td class="px-5 py-4">{{ $task->area->name }}</td>
                        <td class="whitespace-nowrap px-5 py-4">
                            {{ $task->due_at->format('d M Y') }}
                            @if ($task->isPastDue())
                                <span class="ml-1 text-xs text-red-600">(lewat)</span>
                            @endif
                        </td>
                        <td class="px-5 py-4"><x-status-badge :status="$submission?->status" /></td>
                        <td class="px-5 py-4 text-right text-xs font-medium">
                            <a href="{{ route('submissions.show', $task) }}" class="text-[#16213A] hover:text-[#A16207]">
                                {{ $task->submissionBlockedReason($submission) ? 'Lihat' : ($submission ? 'Kumpul Ulang' : 'Kumpulkan') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center text-slate-500">Belum ada tugas dari supervisor.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $tasks->links() }}</div>
@endsection
