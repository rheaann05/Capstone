<?php


use Livewire\Component;


new  class extends Component
{
    //
};
?>


<div>
<div class="w-full"> <div data-hs-carousel='{
      "loadingClasses": "opacity-0",
      "isAutoPlay": true
    }' class="relative">
   
    <div class="hs-carousel relative overflow-hidden w-full h-[60vh] md:h-screen bg-gray-100">
      <div class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
       
        <div class="hs-carousel-slide">
          <div class="h-full flex flex-col bg-[linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.6)),url('https://images.pexels.com/photos/37129973/pexels-photo-37129973.jpeg')] bg-cover bg-center bg-no-repeat">
            <div class="mt-auto w-full md:max-w-2xl ps-8 pb-12 md:ps-16 md:pb-20">
              <span class="block text-white font-medium tracking-widest uppercase text-sm mb-2">Gawahon</span>
              <span class="block text-white text-3xl md:text-6xl font-bold leading-tight">Experience the pristine beauty of cascading falls</span>
              <div class="mt-8">
                <a class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl bg-white text-gray-800 hover:bg-gray-100 transition-all focus:outline-none" href="#">
                  Explore Falls
                </a>
              </div>
            </div>
          </div>
        </div>


        <div class="hs-carousel-slide">
          <div class="h-full flex flex-col bg-[linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.6)),url('https://images.pexels.com/photos/37130189/pexels-photo-37130189.jpeg')] bg-cover bg-center bg-no-repeat">
            <div class="mt-auto w-full md:max-w-2xl ps-8 pb-12 md:ps-16 md:pb-20">
              <span class="block text-white font-medium tracking-widest uppercase text-sm mb-2">ecoTrail</span>
              <span class="block text-white text-3xl md:text-6xl font-bold leading-tight">Walk through the serene heart of the mangroves</span>
              <div class="mt-8">
                <a class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl bg-white text-gray-800 hover:bg-gray-100 transition-all focus:outline-none" href="#">
                  Discover Trails
                </a>
              </div>
            </div>
          </div>
        </div>


        <div class="hs-carousel-slide">
          <div class="h-full flex flex-col bg-[linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.6)),url('https://images.pexels.com/photos/37129816/pexels-photo-37129816.jpeg')] bg-cover bg-center bg-no-repeat">
            <div class="mt-auto w-full md:max-w-2xl ps-8 pb-12 md:ps-16 md:pb-20">
              <span class="block text-white font-medium tracking-widest uppercase text-sm mb-2">Motorpool</span>
              <span class="block text-white text-3xl md:text-6xl font-bold leading-tight">Relax and unwind in our crystal clear waters</span>
              <div class="mt-8">
                <a class="py-3 px-8 inline-flex items-center gap-x-2 text-sm font-semibold rounded-xl bg-white text-gray-800 hover:bg-gray-100 transition-all focus:outline-none" href="#">
                  Book a Stay
                </a>
              </div>
            </div>
          </div>
        </div>


      </div>
    </div>


    <button type="button" class="hs-carousel-prev hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-16 h-full text-white hover:bg-white/10 focus:outline-none transition-colors">
      <span class="text-3xl" aria-hidden="true">
        <svg class="shrink-0 size-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </span>
      <span class="sr-only">Previous</span>
    </button>


    <button type="button" class="hs-carousel-next hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-16 h-full text-white hover:bg-white/10 focus:outline-none transition-colors">
      <span class="sr-only">Next</span>
      <span class="text-3xl" aria-hidden="true">
        <svg class="shrink-0 size-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </span>
    </button>


    <div class="hs-carousel-pagination flex justify-center absolute bottom-6 start-0 end-0 space-x-3">
      <span class="hs-carousel-active:bg-white hs-carousel-active:border-white size-3 border-2 border-white/50 rounded-full cursor-pointer transition-all"></span>
      <span class="hs-carousel-active:bg-white hs-carousel-active:border-white size-3 border-2 border-white/50 rounded-full cursor-pointer transition-all"></span>
      <span class="hs-carousel-active:bg-white hs-carousel-active:border-white size-3 border-2 border-white/50 rounded-full cursor-pointer transition-all"></span>
    </div>
  </div>
</div>






<section class="py-20 bg-[#F7F6F1]">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
 
        {{-- Section Header --}}
        <div class="flex flex-wrap justify-between items-end mb-8 gap-6">
            <div>
                <p class="text-[#7E8A74] font-bold tracking-[0.2em] uppercase text-xs mb-2">
                    Curated Experiences
                </p>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#1B261D] tracking-tight leading-tight">
                    Popular Destinations
                </h2>
            </div>
            <a href="#"
               class="py-3 px-7 inline-flex items-center gap-x-2 text-sm font-semibold rounded-full bg-[#1B261D] text-white hover:bg-[#2d4a35] transition-colors duration-200 focus:outline-none">
                View All Sites
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
 
        {{-- Filter Tabs (Preline nav-tabs style) --}}
        <nav x-data="{ active: 'all' }" class="flex flex-wrap gap-2 mb-10">
            @foreach (['all' => 'All', 'historic' => 'Historic', 'adventure' => 'Adventure', 'culinary' => 'Culinary', 'nature' => 'Nature'] as $key => $label)
                <button
                    @click="active = '{{ $key }}'"
                    :class="active === '{{ $key }}'
                        ? 'bg-[#1B261D] text-white border-[#1B261D]'
                        : 'bg-transparent text-[#4A554E] border-[#d0cfc0] hover:bg-[#1B261D] hover:text-white hover:border-[#1B261D]'"
                    class="py-2 px-5 text-xs font-bold tracking-[0.15em] uppercase rounded-full border-2 transition-all duration-200 focus:outline-none">
                    {{ $label }}
                </button>
            @endforeach
 
            {{-- Filter indicator --}}
            <div x-data="{ active: 'all' }"></div>
        </nav>
 
        {{-- Destination Cards --}}
        @php
        $destinations = [
            [
                'category'   => 'historic',
                'badge'      => 'Historic',
                'badge_cls'  => 'bg-[#D6D5C3] text-[#3a3a2a]',
                'image'      => 'https://images.unsplash.com/photo-1544731612-de7f96ffe55f?q=80&w=800',
                'title'      => 'St. Joseph Chapel',
                'desc'       => "Home to the famous 'Angry Christ' mural by Alfonso Ossorio — a masterpiece of modern religious art.",
                'rating'     => 4.2,
                'reviews'    => 128,
                'distance'   => '0.8 km',
                'tags'       => ['Free entry', 'Guided tours'],
            ],
            [
                'category'   => 'adventure',
                'badge'      => 'Adventure',
                'badge_cls'  => 'bg-[#c5e0c9] text-[#1B3d20]',
                'image'      => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=800',
                'title'      => 'Nature Trails',
                'desc'       => 'Escape into lush greenery through local nature reserves and sprawling hiking paths.',
                'rating'     => 4.8,
                'reviews'    => 94,
                'distance'   => '2.1 km',
                'tags'       => ['Hiking', 'Wildlife'],
            ],
            [
                'category'   => 'culinary',
                'badge'      => 'Culinary',
                'badge_cls'  => 'bg-[#f5d9b5] text-[#5a3010]',
                'image'      => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=800',
                'title'      => 'Local Flavors',
                'desc'       => 'Authentic Sugar Bowl cuisine and handcrafted local delicacies at the heart of Negros.',
                'rating'     => 4.5,
                'reviews'    => 211,
                'distance'   => '1.3 km',
                'tags'       => ['Food tour', 'Local'],
            ],
            [
                'category'   => 'nature',
                'badge'      => 'Nature',
                'badge_cls'  => 'bg-[#b8d4c2] text-[#1a3826]',
                'image'      => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?q=80&w=800',
                'title'      => 'Sugar Fields',
                'desc'       => 'Rolling cane fields stretching to the horizon — the iconic landscape that defines Victorias City.',
                'rating'     => 4.3,
                'reviews'    => 76,
                'distance'   => '3.5 km',
                'tags'       => ['Scenic', 'Photography'],
            ],
        ];
        @endphp
 
        <div
            x-data="{ active: 'all', saved: {} }"
            class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5"
        >
            @foreach ($destinations as $i => $d)
            <div
                x-show="active === 'all' || active === '{{ $d['category'] }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="group flex flex-col bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1.5"
            >
                {{-- Image --}}
                <div class="relative h-52 overflow-hidden">
                    <img
                        src="{{ $d['image'] }}"
                        alt="{{ $d['title'] }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        loading="lazy"
                    >
 
                    {{-- Badge --}}
                    <span class="absolute top-3 left-3 {{ $d['badge_cls'] }} px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        {{ $d['badge'] }}
                    </span>
 
                    {{-- Save / Wishlist toggle --}}
                    <button
                        @click="saved[{{ $i }}] = !saved[{{ $i }}]"
                        class="absolute top-3 right-3 size-8 rounded-full bg-white/85 hover:bg-white flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/60"
                        :aria-label="saved[{{ $i }}] ? 'Remove from wishlist' : 'Save to wishlist'"
                    >
                        <svg
                            class="size-3.5 transition-all duration-200"
                            :class="saved[{{ $i }}] ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-[#1B261D]'"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
 
                    {{-- Rating overlay --}}
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-[#1B261D]/70 to-transparent px-3 py-2 flex items-center gap-2">
                        <div class="flex gap-0.5">
                            @for ($s = 1; $s <= 5; $s++)
                                <div
                                    class="size-2.5 {{ $s <= round($d['rating']) ? 'bg-yellow-400' : 'bg-white/35' }}"
                                    style="clip-path:polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)"
                                ></div>
                            @endfor
                        </div>
                        <span class="text-[11px] text-white/90 font-semibold">
                            {{ $d['rating'] }} <span class="text-white/55">({{ $d['reviews'] }})</span>
                        </span>
                    </div>
                </div>
 
                {{-- Body --}}
                <div class="flex flex-col flex-1 p-5">
                    <div class="flex justify-between items-start mb-1.5 gap-2">
                        <h3 class="text-[15px] font-extrabold text-[#1B261D] leading-snug">
                            {{ $d['title'] }}
                        </h3>
                        <span class="shrink-0 flex items-center gap-1 text-[11px] text-[#7E8A74] font-semibold">
                            <svg class="size-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $d['distance'] }}
                        </span>
                    </div>
 
                    <p class="text-sm text-[#4A554E] leading-relaxed mb-4 flex-1">
                        {{ $d['desc'] }}
                    </p>
 
                    <div class="flex justify-between items-center gap-2 pt-1 border-t border-[#f0efe8]">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($d['tags'] as $tag)
                                <span class="text-[10px] bg-[#F0EFEA] text-[#4A554E] px-2.5 py-1 rounded-full font-semibold">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                        <button
                            type="button"
                            class="shrink-0 py-1.5 px-3.5 text-[11px] font-bold rounded-full border-2 border-[#1B261D] text-[#1B261D] hover:bg-[#1B261D] hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#1B261D]/30"
                        >
                            Explore
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
 
        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-10">
            @foreach ([
                ['24+',  'Destinations',  'Curated sites'],
                ['4.6',  'Avg. Rating',   'From verified visitors'],
                ['12k',  'Visitors / yr', 'And counting'],
                ['Free', 'Entry Spots',   'No cost required'],
            ] as [$num, $lbl, $sub])
            <div class="bg-white rounded-2xl p-5 text-center border border-[#eeeee6] hover:shadow-md transition-shadow duration-300">
                <div class="text-3xl font-extrabold text-[#1B261D]">{{ $num }}</div>
                <div class="text-[11px] font-bold tracking-widest uppercase text-[#7E8A74] mt-1">{{ $lbl }}</div>
                <div class="text-[11px] text-[#9CA8A3] mt-0.5">{{ $sub }}</div>
            </div>
            @endforeach
        </div>
 
    </div>
</section>
 
 
{{-- =============================================
     INTERACTIVE MAP
     ============================================= --}}


 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


    <style>
        /* Custom Leaflet Dark Theme Overrides */
        .leaflet-container { background: #162019 !important; border-radius: 1rem; }
        .leaflet-popup-content-wrapper { background: #2d4a35 !important; color: white !important; border-radius: 0.5rem !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3) !important; }
        .leaflet-popup-tip { background: #2d4a35 !important; }
        .leaflet-popup-content { color: #e8f5ec !important; font-size: 13px !important; margin: 12px !important; }
        .leaflet-control-zoom a { background: #1B261D !important; color: #8fc99a !important; border-color: rgba(255,255,255,0.1) !important; }
        .leaflet-control-attribution { background: rgba(0,0,0,0.4) !important; color: rgba(255,255,255,0.3) !important; font-size: 10px !important; }
        .leaflet-control-attribution a { color: rgba(255,255,255,0.4) !important; }
       
        /* Custom Marker pulse effect (optional) */
        .custom-pin {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pin-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
    </style>
</head>
<body class="bg-[#1B261D] antialiased">


<section class="py-12 lg:py-20 bg-[#1B261D]">
  <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-5 gap-10 items-start">


      <div class="lg:col-span-3 order-2 lg:order-1">
        <p class="text-[#8fc99a] font-bold tracking-[0.2em] uppercase text-xs mb-2 flex items-center gap-2">
          <span class="inline-block w-5 h-px bg-[#8fc99a]"></span>
          Explore the Region
        </p>
        <h3 class="text-3xl font-extrabold text-white mb-5 leading-snug">
          Interactive Map
        </h3>
        <div id="map" class="w-full h-[450px] lg:h-[500px] rounded-2xl border border-white/10 overflow-hidden shadow-2xl"></div>
      </div>


      <div class="lg:col-span-2 pt-2 order-1 lg:order-2">
        <p class="text-white/40 text-[10px] font-bold tracking-widest uppercase mb-4">
          Nearby Destinations
        </p>


        <div id="location-list" class="flex flex-col gap-2 mb-8">
            <button onclick="focusLocation(0)" class="group flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/8 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-left w-full focus:outline-none focus:ring-1 focus:ring-[#8fc99a]">
              <span class="size-2.5 rounded-full shrink-0" style="background:#8fc99a"></span>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] text-white/80 font-semibold truncate">St. Joseph Chapel</p>
              </div>
              <span class="text-[11px] text-white/35 font-medium shrink-0">0.8 km</span>
            </button>


            <button onclick="focusLocation(1)" class="group flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/8 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-left w-full focus:outline-none focus:ring-1 focus:ring-[#7bcce0]">
              <span class="size-2.5 rounded-full shrink-0" style="background:#7bcce0"></span>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] text-white/80 font-semibold truncate">Gawahon Eco Park</p>
              </div>
              <span class="text-[11px] text-white/35 font-medium shrink-0">1.2 km</span>
            </button>


            <button onclick="focusLocation(2)" class="group flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/8 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-left w-full focus:outline-none focus:ring-1 focus:ring-[#f5c87a]">
              <span class="size-2.5 rounded-full shrink-0" style="background:#f5c87a"></span>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] text-white/80 font-semibold truncate">Local Flavors Market</p>
              </div>
              <span class="text-[11px] text-white/35 font-medium shrink-0">1.3 km</span>
            </button>


            <button onclick="focusLocation(3)" class="group flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/8 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-left w-full focus:outline-none focus:ring-1 focus:ring-[#c9a8f5]">
              <span class="size-2.5 rounded-full shrink-0" style="background:#c9a8f5"></span>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] text-white/80 font-semibold truncate">Nature Trails</p>
              </div>
              <span class="text-[11px] text-white/35 font-medium shrink-0">2.1 km</span>
            </button>


            <button onclick="focusLocation(4)" class="group flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/8 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-left w-full focus:outline-none focus:ring-1 focus:ring-[#f5a07a]">
              <span class="size-2.5 rounded-full shrink-0" style="background:#f5a07a"></span>
              <div class="flex-1 min-w-0">
                <p class="text-[13px] text-white/80 font-semibold truncate">Sugar Fields</p>
              </div>
              <span class="text-[11px] text-white/35 font-medium shrink-0">3.5 km</span>
            </button>
        </div>


        <p class="text-[#9CA8A3] text-sm leading-relaxed mb-6">
          Click on any map pin or the locations above to discover historical sites and nature escapes. Plan your full-day route in minutes.
        </p>


        <a href="https://www.google.com/maps" target="_blank"
           class="inline-flex items-center gap-x-2 py-3 px-6 text-sm font-semibold rounded-full bg-white text-[#1B261D] hover:bg-[#e8f5ec] transition-colors duration-200 focus:outline-none">
          Get Directions
          <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>


<script>
    // 1. Data Configuration
    const locations = [
        { name: "St. Joseph Chapel", coords: [10.8980, 123.0000], color: "#8fc99a", dist: "0.8 km" },
        { name: "Gawahon Eco Park", coords: [10.9150, 123.0200], color: "#7bcce0", dist: "1.2 km" },
        { name: "Local Flavors Market", coords: [10.8920, 123.0150], color: "#f5c87a", dist: "1.3 km" },
        { name: "Nature Trails", coords: [10.9250, 122.9900], color: "#c9a8f5", dist: "2.1 km" },
        { name: "Sugar Fields", coords: [10.8800, 123.0400], color: "#f5a07a", dist: "3.5 km" }
    ];


    // 2. Initialize Map (Centered on the average of locations)
    const map = L.map('map', {
        center: [10.9000, 123.0100],
        zoom: 13,
        zoomControl: true
    });


    // 3. Add Dark Theme Tiles (CartoDB Dark Matter)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
    }).addTo(map);


    // 4. Custom Marker Creation
    const markers = [];
    locations.forEach((loc, index) => {
        const customIcon = L.divIcon({
            className: 'custom-pin',
            html: `<div class="pin-dot" style="background: ${loc.color}; box-shadow: 0 0 10px ${loc.color};"></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });


        const marker = L.marker(loc.coords, { icon: customIcon })
            .bindPopup(`<strong>${loc.name}</strong><br/>Distance: ${loc.dist}`)
            .addTo(map);
       
        markers.push(marker);
    });


    // 5. Function to link Sidebar to Map
    function focusLocation(index) {
        const loc = locations[index];
        map.flyTo(loc.coords, 15, {
            duration: 1.5
        });
        setTimeout(() => {
            markers[index].openPopup();
        }, 1200);
    }
</script>


</body>


<!-- CTA BANNER -->
<section class="relative py-24 overflow-hidden" style="background: #F7F6F1;">
  <div class="absolute inset-0 opacity-60"
       style="background-image: radial-gradient(circle, rgba(27,38,29,0.07) 1px, transparent 1px); background-size: 28px 28px;">
  </div>


  <div class="relative max-w-2xl mx-auto px-4 text-center">
    <p class="text-[#7E8A74] font-bold tracking-[0.2em] uppercase text-xs mb-3">
      Ready to Visit?
    </p>
    <h2 class="text-4xl md:text-5xl font-extrabold text-[#1B261D] tracking-tight leading-tight mb-4">
      Plan your perfect day in Victorias
    </h2>
    <p class="text-[#4A554E] text-base md:text-lg leading-relaxed mb-8 max-w-lg mx-auto">
      Whether you're chasing history, flavors, or fresh air — let us help you build an itinerary that fits your pace.
    </p>
 


</div>

