<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#F7F6F2] px-6 py-12 text-slate-700">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <span class="text-xs uppercase tracking-[0.15em] text-slate-400">Sistem Astra</span>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Daftar Akun Dealer</h1>
            <p class="mt-1 text-sm text-slate-500">Buat akun untuk mengumpulkan tugas dari supervisor.</p>
        </div>

        <form action="{{ route('register-Post') }}" method="POST" class="space-y-6 border border-[#E5E3DB] bg-white p-8">
            @csrf
            <div>
                <label for="name" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[#16213A]">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                @error('name') <p class="pt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[#16213A]">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                @error('email') <p class="pt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="dealer_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[#16213A]">Dealer</label>
                <select id="dealer_id" name="dealer_id"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                    <option value="">Pilih dealer</option>
                    @foreach ($dealers as $dealer)
                        <option value="{{ $dealer->id }}" @selected((string) old('dealer_id') === (string) $dealer->id)>{{ $dealer->code }} - {{ $dealer->name }}</option>
                    @endforeach
                </select>
                @error('dealer_id') <p class="pt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[#16213A]">Kata Sandi</label>
                <input type="password" id="password" name="password"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                @error('password') <p class="pt-2 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[#16213A]">Ulangi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
            </div>

            <button type="submit" class="w-full cursor-pointer bg-[#16213A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Daftar</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login-view') }}" class="font-medium text-[#16213A] hover:text-[#A16207]">Masuk</a>
        </p>
    </div>
</body>
</html>
