@props(['type' => 'SUCCESS'])

@php
    $styles = [
        'SUCCESS' => ['border-green-500 bg-green-100 text-green-800', 'Berhasil'],
        'ERROR' => ['border-red-500 bg-red-100 text-red-800', 'Gagal'],
        'WARNING' => ['border-yellow-500 bg-yellow-100 text-yellow-800', 'Perhatian'],
        'INFO' => ['border-blue-500 bg-blue-100 text-blue-800', 'Info'],
    ];
    [$class, $heading] = $styles[$type] ?? $styles['INFO'];
@endphp

<div class="rounded-lg border p-4 {{ $class }}">
    <p class="font-semibold">{{ $heading }}</p>
    <p class="text-sm">{{ $slot }}</p>
</div>
