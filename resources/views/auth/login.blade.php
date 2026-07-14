<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Resident Auditor Bank Sulteng') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center bg-cover bg-center" style="background-color: #f3f4f6; background-image: radial-gradient(circle at center, #ffffff 0%, #f3f4f6 100%);">

    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-slate-100">
        <div class="flex flex-col items-center mb-8">
            <div class="mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bank Sulteng" class="h-16 w-auto">
            </div>
            <h2 class="text-2xl font-bold text-slate-800 text-center">Aplikasi Resident Auditor</h2>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 flex items-center gap-3 rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3">
                <svg class="h-5 w-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-[#174b84] sm:text-sm sm:leading-6 px-3">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-slate-900">Password</label>
                </div>
                <div class="mt-2">
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-[#174b84] sm:text-sm sm:leading-6 px-3">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-[#174b84] focus:ring-[#174b84]">
                    <label for="remember" class="ml-3 block text-sm leading-6 text-slate-900">Ingat Saya</label>
                </div>
            </div>

            <div>
                <button type="submit" class="flex w-full justify-center rounded-md bg-[#174b84] px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#123964] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#174b84] transition-colors">
                    Masuk ke Sistem
                </button>
            </div>
        </form>
        
        <div class="mt-8 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} PT Bank Sulteng. All rights reserved.
        </div>
    </div>

</body>
</html>
