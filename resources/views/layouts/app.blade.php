<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title')
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-[#F7F6F2] text-slate-700">
    {{-- header start --}}
    @include('layouts.partials.header')
    {{-- header end --}}

    {{-- content start --}}
    <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-10">
        <div class="mb-6 space-y-3">
            @if (session('success'))
                <x-alert type="SUCCESS">{{ session('success') }}</x-alert>
            @endif
            @if (session('error'))
                <x-alert type="ERROR">{{ session('error') }}</x-alert>
            @endif
        </div>

        @yield('content')
    </main>
    {{-- content end --}}

    {{-- footer start --}}
    @include('layouts.partials.footer')
    {{-- footer end --}}
</body>
</html>
