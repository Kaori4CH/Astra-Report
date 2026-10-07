@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 flex items-end justify-between border-b border-[#E5E3DB] pb-5">
        <div>
            <a href="{{ route('dealers.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Daftar Dealer</a>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Detail Dealer</h1>
        </div>
        <a href="{{ route('dealers.edit', $dealer) }}"
            class="bg-[#16213A] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Ubah</a>
    </div>

    <dl class="grid gap-6 border border-[#E5E3DB] bg-white p-8 sm:grid-cols-2">
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Kode Dealer</dt>
            <dd class="mt-1 font-mono text-[#16213A]">{{ $dealer->code }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Nama Dealer</dt>
            <dd class="mt-1 font-medium text-[#16213A]">{{ $dealer->name }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Dibuat</dt>
            <dd class="mt-1">{{ $dealer->created_at->format('d M Y H:i') }}</dd>
        </div>
        <div>
            <dt class="text-[11px] uppercase tracking-[0.15em] text-slate-400">Diperbarui</dt>
            <dd class="mt-1">{{ $dealer->updated_at->format('d M Y H:i') }}</dd>
        </div>
    </dl>
@endsection
