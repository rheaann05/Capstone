<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Tenant;

new 
#[Layout('layouts.app')]
#[Title('Explore Map')]
class extends Component {
    public $selectedTenantId = null;

    public function getTenantsProperty()
    {
        return Tenant::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('name')
            ->get();
    }

    public function updatedSelectedTenantId($value)
    {
        if ($value) {
            $tenant = Tenant::find($value);
            if ($tenant && $tenant->latitude && $tenant->longitude) {
                $this->dispatch('fly-to-tenant', tenant: $tenant->toArray());
            }
        }
    }
}
?>

<div class="min-h-screen bg-slate-50">
    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 shadow-sm py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">Explore Map</h1>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">&larr; Back to Home</a>
        </div>
    </header>

    {{-- Filter Bar --}}
    <div class="bg-white border-b border-slate-200 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4">
            {{-- Tenant Selector Dropdown --}}
            <div class="relative w-64">
                <select wire:model.live="selectedTenantId" class="w-full rounded-lg border-slate-300 text-sm py-2 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select a business --</option>
                    @foreach($this->tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Map Container --}}
    <div class="relative h-[calc(100vh-140px)] w-full"
         x-data="{
            map: null,
            markers: [],
            tenantData: @js($this->tenants),
            init() {
                let check = setInterval(() => {
                    if (typeof L !== 'undefined') {
                        clearInterval(check);
                        this.initMap();
                    }
                }, 100);
            },
            initMap() {
                // Fix default icon paths
                delete L.Icon.Default.prototype._getIconUrl;
                L.Icon.Default.mergeOptions({
                    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
                    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                });

                let centerLat = 10.900977766937142;
                let centerLng = 123.07055771888716;
                if (this.tenantData.length > 0) {
                    centerLat = parseFloat(this.tenantData[0].latitude);
                    centerLng = parseFloat(this.tenantData[0].longitude);
                }

                this.map = L.map($refs.mapContainer).setView([centerLat, centerLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href=&quot;https://www.openstreetmap.org/copyright&quot;>OpenStreetMap</a>'
                }).addTo(this.map);

                this.plotMarkers(this.tenantData);

                window.addEventListener('fly-to-tenant', (e) => {
                    this.flyToTenant(e.detail.tenant);
                });
            },
            plotMarkers(tenants) {
                this.markers.forEach(m => this.map.removeLayer(m));
                this.markers = [];

                tenants.forEach(tenant => {
                    if (tenant.latitude && tenant.longitude) {
                        let marker = L.marker([parseFloat(tenant.latitude), parseFloat(tenant.longitude)])
                            .bindPopup(`
                                <b>${tenant.name}</b><br>
                                ${tenant.address || ''}<br>
                                <a href=&quot;/business/${tenant.slug}&quot; class=&quot;text-blue-600 hover:underline text-sm&quot;>View Details</a>
                            `);
                        marker.addTo(this.map);
                        this.markers.push(marker);
                    }
                });

                if (tenants.length > 0) {
                    let bounds = L.latLngBounds(tenants.map(t => [parseFloat(t.latitude), parseFloat(t.longitude)]));
                    this.map.fitBounds(bounds, { padding: [50, 50] });
                }
            },
            flyToTenant(tenant) {
                if (!tenant.latitude || !tenant.longitude) return;
                let lat = parseFloat(tenant.latitude);
                let lng = parseFloat(tenant.longitude);
                this.map.flyTo([lat, lng], 15);
                
                // Find and open popup for this tenant
                let marker = this.markers.find(m => {
                    let pos = m.getLatLng();
                    return pos.lat === lat && pos.lng === lng;
                });
                if (marker) {
                    marker.openPopup();
                }
            }
         }">
        <div wire:ignore class="h-full w-full">
            <div x-ref="mapContainer" class="h-full w-full"></div>
        </div>
    </div>
</div>