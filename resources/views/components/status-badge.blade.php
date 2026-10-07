@props(['status' => null])

@php
    $value = $status instanceof \BackedEnum ? $status->value : $status;

    $map = [
        'MENUNGGU' => ['bg-slate-100 text-slate-700', 'MENUNGGU'],
        'REVISI' => ['bg-yellow-100 text-yellow-800', 'REVISI'],
        'DISETUJUI' => ['bg-green-100 text-green-700', 'DISETUJUI'],
        'DITOLAK' => ['bg-red-100 text-red-700', 'DITOLAK'],
    ];
    [$class, $label] = $map[$value] ?? ['bg-slate-100 text-slate-500', 'BELUM DIKUMPULKAN'];
@endphp

<span class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $class }}">{{ $label }}</span>
