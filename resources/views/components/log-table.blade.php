@props(['logs'])

<div class="border border-[#E5E3DB] bg-white">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-[#16213A] text-[11px] uppercase tracking-[0.15em] text-[#16213A]">
                <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                <th class="px-5 py-3.5 font-semibold">Aktivitas</th>
                <th class="px-5 py-3.5 font-semibold">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr class="border-b border-[#EFEDE6]">
                    <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="px-5 py-4 font-medium text-[#16213A]">{{ $log->activity }}</td>
                    <td class="px-5 py-4">{{ $log->note ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-slate-500">Belum ada aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
