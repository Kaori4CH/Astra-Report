@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 flex items-end justify-between border-b border-[#E5E3DB] pb-5">
        <div>
            <p class="mb-1 text-[11px] uppercase tracking-[0.2em] text-[#A16207]">Master Data Dealer</p>
            <h1 class="font-display text-3xl font-semibold text-[#16213A]">Daftar Dealer</h1>
        </div>
        <a href="{{ route('dealers.create') }}"
            class="bg-[#16213A] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">
            Tambah Dealer
        </a>
    </div>

    <form method="GET" action="{{ route('dealers.index') }}" class="mb-6 flex flex-wrap items-end gap-3 border border-[#E5E3DB] bg-white p-5">
        <div class="min-w-55 flex-1">
            <label for="search" class="mb-1 block text-[11px] uppercase tracking-[0.15em] text-[#16213A]">Cari</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Kode atau nama dealer"
                class="w-full border border-[#E5E3DB] px-3 py-2 text-sm focus:border-[#16213A] focus:outline-none">
        </div>
        <button type="submit" class="bg-[#16213A] px-5 py-2 text-sm font-medium text-white transition hover:bg-[#26324f]">Terapkan</button>
    </form>

    <div class="border border-[#E5E3DB] bg-white">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-[#16213A] text-[11px] uppercase tracking-[0.15em] text-[#16213A]">
                    <th class="w-14 px-5 py-3.5 font-semibold">No.</th>
                    <th class="px-5 py-3.5 font-semibold">Kode Dealer</th>
                    <th class="px-5 py-3.5 font-semibold">Nama Dealer</th>
                    <th class="px-5 py-3.5 text-right font-semibold">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dealers as $dealer)
                    <tr class="border-b border-[#EFEDE6] hover:bg-[#FAF9F5]">
                        <td class="px-5 py-4 font-display text-lg text-[#A16207]">{{ $dealers->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $dealer->code }}</td>
                        <td class="px-5 py-4 font-medium text-[#16213A]">{{ $dealer->name }}</td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-4 text-xs font-medium">
                                <a href="{{ route('dealers.show', $dealer) }}" class="text-[#16213A] hover:text-[#A16207]">Lihat</a>
                                <a href="{{ route('dealers.edit', $dealer) }}" class="text-[#16213A] hover:text-[#A16207]">Ubah</a>
                                <form action="{{ route('dealers.destroy', $dealer) }}" method="POST"
                                    onsubmit="return confirm('Hapus data dealer ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cursor-pointer text-red-700 hover:text-red-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-slate-500">Data dealer belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $dealers->links() }}
    </div>
@endsection
