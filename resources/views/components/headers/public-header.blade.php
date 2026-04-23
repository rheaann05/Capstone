{{-- resources/views/components/headers/public-header.blade.php --}}
<div x-data="{ mobileOpen: false, secondaryOpen: false, userMenuOpen: false }">
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 shadow-sm">
        <nav class="relative max-w-[85rem] w-full mx-auto md:flex md:items-center md:justify-between md:gap-3 py-3 px-4 sm:px-6 lg:px-8">
            {{-- Logo & Brand --}}
            <div class="flex justify-between items-center gap-x-1">
                <a class="flex-none font-bold text-2xl text-slate-800 focus:outline-hidden focus:opacity-80 flex items-center gap-2" href="{{ route('home') }}" wire:navigate aria-label="Brand">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>System<span class="text-blue-600">App</span></span>
                </a>

                {{-- Mobile Toggle --}}
                <button @click="mobileOpen = !mobileOpen" type="button" class="md:hidden relative size-9 flex justify-center items-center font-medium text-sm rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100">
                    <svg x-show="!mobileOpen" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="sr-only">Toggle navigation</span>
                </button>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex md:items-center md:gap-1 md:ml-6 grow">
                <a class="py-2 px-3 flex items-center text-sm font-medium rounded-lg hover:bg-slate-100 {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('home') }}" wire:navigate>
                    Home
                </a>
                <a class="py-2 px-3 flex items-center text-sm font-medium rounded-lg hover:bg-slate-100 {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('about') }}" wire:navigate>
                    About
                </a>
                @auth
                <a class="py-2 px-3 flex items-center text-sm font-medium rounded-lg hover:bg-slate-100 {{ request()->routeIs('explore.map') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('explore.map') }}" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Map
                </a>
                @endauth
                <a class="py-2 px-3 flex items-center text-sm font-medium rounded-lg hover:bg-slate-100 {{ request()->routeIs('public.bookings') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('public.bookings') }}" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Book Now
                </a>
            </div>

            {{-- Divider --}}
            <div class="hidden md:block mx-2">
                <div class="w-px h-6 bg-slate-200"></div>
            </div>

            {{-- User Section --}}
            <div class="hidden md:flex md:items-center md:gap-2">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-white text-slate-700 shadow-sm align-middle hover:bg-slate-50 border border-slate-200">
                            <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->name ?? 'User' }}</span>
                            <svg :class="{ 'rotate-180': open }" class="size-4 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition.opacity.duration.200ms class="absolute right-0 mt-2 min-w-48 bg-white shadow-md rounded-lg p-1 border border-slate-200 z-50">
                            <div class="py-2 px-3 border-b border-slate-200">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-slate-700 hover:bg-slate-100" href="{{ route('home') }}" wire:navigate>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">Sign in</a>
                    <a href="{{ route('register') }}" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">Get started</a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Secondary Navigation Bar --}}
    <nav class="bg-slate-50 border-b border-slate-200">
        <div class="max-w-[85rem] w-full mx-auto sm:flex sm:flex-row sm:justify-between sm:items-center sm:gap-x-3 py-2 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center gap-x-3">
                <div class="grow">
                    <span class="font-semibold whitespace-nowrap text-slate-600 text-xs uppercase tracking-wider">Discover</span>
                </div>
                <button @click="secondaryOpen = !secondaryOpen" type="button" class="sm:hidden py-1.5 px-2 inline-flex items-center font-medium text-xs rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm hover:bg-slate-50">
                    Menu
                    <svg :class="{ 'rotate-180': secondaryOpen }" class="size-4 ms-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>

            <div x-show="secondaryOpen" x-collapse x-cloak class="sm:hidden">
                <div class="py-2 flex flex-col gap-y-2">
                    @auth
                    <a class="text-sm text-slate-600 hover:text-blue-600" href="{{ route('explore.map') }}" wire:navigate>Interactive Map</a>
                    @endauth
                    <a class="text-sm text-slate-600 hover:text-blue-600" href="{{ route('public.bookings') }}" wire:navigate">Book a Stay</a>
                    <a class="text-sm text-slate-600 hover:text-blue-600" href="{{ route('learnmore') }}" wire:navigate">Learn More</a>
                </div>
            </div>

            <div class="hidden sm:flex sm:flex-row sm:justify-end gap-x-6">
                @auth
                <a class="text-sm font-medium text-slate-600 hover:text-blue-600" href="{{ route('explore.map') }}" wire:navigate>Interactive Map</a>
                @endauth
                <a class="text-sm font-medium text-slate-600 hover:text-blue-600" href="{{ route('public.bookings') }}" wire:navigate>Book a Stay</a>
                <a class="text-sm font-medium text-slate-600 hover:text-blue-600" href="{{ route('learnmore') }}" wire:navigate>Learn More</a>
            </div>
        </div>
    </nav>

    {{-- Mobile Menu (Slide-out) --}}
    <div x-show="mobileOpen" x-collapse x-cloak class="md:hidden bg-white border-b border-slate-200">
        <div class="px-4 py-3 space-y-1">
            <a class="block py-2 px-3 rounded-lg {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('home') }}" wire:navigate>Home</a>
            <a class="block py-2 px-3 rounded-lg {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('about') }}" wire:navigate>About</a>
            @auth
            <a class="block py-2 px-3 rounded-lg {{ request()->routeIs('explore.map') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('explore.map') }}" wire:navigate>Map</a>
            @endauth
            <a class="block py-2 px-3 rounded-lg {{ request()->routeIs('public.bookings') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('public.bookings') }}" wire:navigate>Book Now</a>
            <a class="block py-2 px-3 rounded-lg {{ request()->routeIs('learnmore') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}" href="{{ route('learnmore') }}" wire:navigate>Learn More</a>
            <div class="border-t border-slate-200 my-2"></div>
            @auth
                <div class="px-3 py-2">
                    <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3 text-left text-sm text-red-600 hover:bg-red-50 rounded-lg">Sign out</button>
                </form>
            @else
                <a class="block py-2 px-3 rounded-lg text-slate-600 hover:bg-slate-100" href="{{ route('login') }}" wire:navigate>Sign in</a>
                <a class="block py-2 px-3 rounded-lg bg-blue-600 text-white text-center hover:bg-blue-700" href="{{ route('register') }}" wire:navigate>Get started</a>
            @endauth
        </div>
    </div>
</div>