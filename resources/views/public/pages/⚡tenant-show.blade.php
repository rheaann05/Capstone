<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Models\Tenant;

new 
#[Layout('layouts.app')]
#[Title('Business Details')]
class extends Component {
    public Tenant $tenant;

    public function mount($slug)
    {
        $this->tenant = Tenant::where('slug', $slug)->firstOrFail();
    }

    #[Computed]
    public function properties()
    {
        return $this->tenant->properties()
            ->where('is_active', true)
            ->where('status', 'available')
            ->with(['images', 'propertyType'])
            ->orderBy('name')
            ->get();
    }
}
?>

<div class="min-h-screen bg-slate-100/50 pb-12" x-data="{ previewImage: null }">
    {{-- Image Preview Modal --}}
    <div x-show="previewImage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" @click.self="previewImage = null" @keydown.escape.window="previewImage = null">
        <div class="relative max-w-5xl max-h-[90vh]">
            <button @click="previewImage = null" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-sm font-medium">Close</button>
            <img :src="previewImage" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl border-4 border-white">
        </div>
    </div>

    {{-- Cover Section with Integrated Map --}}
    <div class="bg-white border-b border-slate-200 shadow-sm relative">
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-100"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 relative z-10">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                {{-- Left Column: Business Info --}}
                <div class="flex-1 w-full bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        {{-- Logo --}}
                        <div class="flex-shrink-0">
                            @if($tenant->logo)
                                <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="h-24 w-24 object-cover rounded-2xl border-4 border-white shadow-md ring-1 ring-slate-100">
                            @else
                                <div class="h-24 w-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-4xl shadow-md border-4 border-white ring-1 ring-slate-100">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-1">
                                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $tenant->name }}</h1>
                                <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200/50">
                                    {{ $tenant->typeOfTenant->type ?? 'Business' }}
                                </span>
                            </div>
                            
                            <div class="mt-4 flex flex-col sm:flex-row sm:flex-wrap gap-y-2 gap-x-6">
                                @if($tenant->address)
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="font-medium">{{ $tenant->address }}</span>
                                </div>
                                @endif
                                
                                @if($tenant->contact_number)
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span class="font-medium">{{ $tenant->contact_number }}</span>
                                </div>
                                @endif

                                @if($tenant->email)
                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <a href="mailto:{{ $tenant->email }}" class="font-medium hover:text-blue-600 transition-colors">{{ $tenant->email }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap gap-3">
                        @if($this->properties->isNotEmpty())
                            @auth
                                <a href="{{ route('booking.create', ['property' => $this->properties->first()->id]) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium py-2 px-5 rounded-xl shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Book Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium py-2 px-5 rounded-xl shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Login to Book
                                </a>
                            @endauth
                        @endif
                        
                        @if($tenant->latitude && $tenant->longitude)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $tenant->latitude }},{{ $tenant->longitude }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-medium py-2 px-5 rounded-xl shadow-sm transition-all duration-200">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Get Directions
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Right Column: Map --}}
                @if($tenant->latitude && $tenant->longitude)
                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full ring-1 ring-slate-900/5">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between">
                            <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Location
                            </h3>
                        </div>
                        <div class="h-64 lg:h-[220px] w-full relative z-0"
                             x-data="{ 
                                init() { 
                                    let check = setInterval(() => {
                                        if (typeof L !== 'undefined') {
                                            clearInterval(check);
                                            delete L.Icon.Default.prototype._getIconUrl;
                                            L.Icon.Default.mergeOptions({
                                                iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
                                                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
                                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                            });
                                            let map = L.map($refs.miniMap).setView([{{ $tenant->latitude }}, {{ $tenant->longitude }}], 14);
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
                                                maxZoom: 19,
                                                attribution: '&copy; <a href=&quot;https://www.openstreetmap.org/copyright&quot;>OpenStreetMap</a>'
                                            }).addTo(map);
                                            L.marker([{{ $tenant->latitude }}, {{ $tenant->longitude }}]).addTo(map);
                                            setTimeout(() => map.invalidateSize(), 100);
                                        }
                                    }, 100);
                                }
                             }">
                            <div wire:ignore x-ref="miniMap" class="h-full w-full"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Property Listings --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Available Accommodations</h2>
            <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">{{ $this->properties->count() }} total</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($this->properties as $property)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group overflow-hidden">
                    
                    {{-- Image Section (Click to Preview) --}}
                    <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden cursor-pointer" 
                         @click="if({{ $property->images->isNotEmpty() ? 'true' : 'false' }}) previewImage = '{{ $property->images->isNotEmpty() ? asset('storage/' . $property->images->first()->image_path) : '' }}'">
                        {{-- Floating Status Badge --}}
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-white/90 text-green-700 backdrop-blur-sm shadow-sm ring-1 ring-black/5">
                                {{ ucfirst($property->status) }}
                            </span>
                        </div>

                        @if($property->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

                            @if($property->images->count() > 1)
                                <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md text-white text-xs font-medium px-2.5 py-1 rounded-lg flex items-center gap-1 border border-white/20 pointer-events-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    +{{ $property->images->count() - 1 }}
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm font-medium">No image available</span>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Body Section --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-1">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">{{ $property->propertyType->name ?? 'Property' }}</p>
                            <h3 class="font-bold text-xl text-slate-900 leading-tight text-balance">{{ $property->name }}</h3>
                        </div>
                        
                        <div class="flex items-center gap-2 mt-3 text-sm text-slate-600 bg-slate-50 self-start px-2.5 py-1 rounded-md border border-slate-100">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="font-medium">Sleeps up to {{ $property->capacity }}</span>
                        </div>
                        
                        <p class="text-sm text-slate-500 mt-4 line-clamp-2 flex-1">{{ $property->description }}</p>
                    </div>

                    {{-- Footer Section (Pricing & Action) --}}
                    <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-0.5">Starting at</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-extrabold text-slate-900">₱{{ number_format($property->price, 2) }}</span>
                                <span class="text-sm font-medium text-slate-500">/ night</span>
                            </div>
                        </div>
                        
                        @auth
                            <a href="{{ route('booking.create', ['property' => $property->id]) }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all duration-200 text-sm">
                                Book Now
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all duration-200 text-sm">
                                Login to Book
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border-2 border-dashed border-slate-200 p-16 text-center flex flex-col items-center justify-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-4">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No properties available</h3>
                    <p class="text-slate-500 max-w-sm">We currently don't have any available accommodations listed. Please check back later.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>