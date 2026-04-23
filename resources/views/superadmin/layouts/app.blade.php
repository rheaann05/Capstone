<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Super Admin Platform' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">
    
    {{-- Sidebar --}}
    <x-headers.admin.sidebar />

    {{-- Main Content Wrapper --}}
    <div class="lg:pl-72">
        {{-- Header --}}
        <x-headers.admin.superadmin-header />

        {{-- Page Content --}}
        <main class="py-8 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <x-footers.admin.superadmin-footer />
    </div>

    @livewireScripts
</body>

</html>