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
<body class="flex min-h-screen flex-col bg-[#FFFFFF] text-[#FFFFFFF]-700">
    {{-- header start --}}
    @include('layouts.partials.header')
    {{-- header end --}}

    {{-- conten start --}}
    <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-10">
        @yield('content')
    </main>
    {{-- conten end --}}

    {{-- footer start --}}
    @include('layouts.partials.footer')
    {{-- footer end --}}
</body>
</html>