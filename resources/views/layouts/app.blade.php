><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title')
    </title>
    @vite(['resources/app/css/app.css', 'resources/app/css/js/app.js'])
</head>
<body class="flex min-h-screen- flex-col bg-[#000000] text-[#FFFFFFF]-700">
    {{-- header start --}}
    @include('layouts.partials.header')
    {{-- header end --}}

    {{-- conten start --}}
    @yield('content')
    {{-- conten end --}}

    {{-- footer start --}}
    @include('layouts.partials.footer')
    {{-- footer end --}}
</body>
</html>