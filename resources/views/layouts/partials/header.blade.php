<header class="bg-[#16213A] text-white">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-6 px-6 py-5">
        <a href="{{ auth()->user()->homeUrl() }}" class="flex items-center gap-3">
            <span>
                <span class="font-display block text-lg font-semibold leading-none">Sistem Astra</span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-white/50">Astra Report</span>
            </span>
        </a>

        <nav class="hidden gap-8 text-sm md:flex">
            @if (auth()->user()->isSupervisor())
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Tugas</a>
                <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Pemeriksaan</a>
                <a href="{{ route('dealers.index') }}" class="{{ request()->routeIs('dealers.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Dealer</a>
                <a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Departemen</a>
                <a href="{{ route('areas.index') }}" class="{{ request()->routeIs('areas.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Area</a>
            @else
                <a href="{{ route('submissions.index') }}" class="{{ request()->routeIs('submissions.*') ? 'text-white' : 'text-white/55' }} hover:text-white">Tugas Saya</a>
            @endif
        </nav>

        <div class="flex items-center gap-4 text-sm">
            <span class="hidden text-right leading-tight sm:block">
                <span class="block">{{ auth()->user()->name }}</span>
                <span class="text-[11px] uppercase tracking-[0.15em] text-white/50">
                    {{ auth()->user()->isSupervisor() ? 'Supervisor' : (auth()->user()->dealer?->name ?? 'Dealer') }}
                </span>
            </span>
            <form action="{{ route('logout-Post') }}" method="POST">
                @csrf
                <button type="submit" class="cursor-pointer rounded-lg bg-red-600 px-4 py-2 text-xs font-medium hover:bg-red-700">KELUAR</button>
            </form>
        </div>
    </div>
    <div class="h-0.5 bg-[#A16207]"></div>
</header>
