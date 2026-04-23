<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Property;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\BookingItem;
use App\Models\BookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

new 
#[Layout('layouts.app')]
#[Title('Book Your Stay')]
class extends Component {
    public $check_in;
    public $check_out;
    public $selectedProperties = [];
    public $selectedServices = [];
    public $totalAmount = 0;
    public $nights = 1;

    // Customer info (for guest checkout, required if not logged in)
    public $customerName = '';
    public $customerPhone = '';
    public $customerEmail = '';
    public $customerAddress = '';

    public function mount()
    {
        $this->check_in = now()->format('Y-m-d');
        $this->check_out = now()->addDay()->format('Y-m-d');
        $this->calculateNights();
    }

    public function updatedCheckIn()
    {
        if ($this->check_in && $this->check_out && Carbon::parse($this->check_in)->gte(Carbon::parse($this->check_out))) {
            $this->check_out = Carbon::parse($this->check_in)->addDay()->format('Y-m-d');
        }
        $this->calculateNights();
        $this->calculateTotal();
    }

    public function updatedCheckOut()
    {
        $this->calculateNights();
        $this->calculateTotal();
    }

    public function calculateNights()
    {
        if ($this->check_in && $this->check_out) {
            $this->nights = Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
            $this->nights = max(1, $this->nights);
        }
    }

    public function addProperty($propertyId, $price)
    {
        if (!isset($this->selectedProperties[$propertyId])) {
            $this->selectedProperties[$propertyId] = ['quantity' => 1, 'price' => $price];
        }
        $this->calculateTotal();
    }

    public function removeProperty($propertyId)
    {
        unset($this->selectedProperties[$propertyId]);
        $this->calculateTotal();
    }

    public function updatePropertyQuantity($propertyId, $quantity)
    {
        if (isset($this->selectedProperties[$propertyId])) {
            $this->selectedProperties[$propertyId]['quantity'] = max(1, (int)$quantity);
        }
        $this->calculateTotal();
    }

    public function addService($serviceId, $price)
    {
        if (!isset($this->selectedServices[$serviceId])) {
            $this->selectedServices[$serviceId] = ['quantity' => 1, 'price' => $price];
        } else {
            $this->selectedServices[$serviceId]['quantity']++;
        }
        $this->calculateTotal();
    }

    public function removeService($serviceId)
    {
        unset($this->selectedServices[$serviceId]);
        $this->calculateTotal();
    }

    public function updateServiceQuantity($serviceId, $quantity)
    {
        if (isset($this->selectedServices[$serviceId])) {
            $this->selectedServices[$serviceId]['quantity'] = max(1, (int)$quantity);
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->selectedProperties as $item) {
            $total += $item['price'] * $item['quantity'] * $this->nights;
        }
        foreach ($this->selectedServices as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $this->totalAmount = $total;
    }

    public function getAvailablePropertiesProperty()
    {
        if (!$this->check_in || !$this->check_out) {
            return collect();
        }

        $checkIn = $this->check_in;
        $checkOut = $this->check_out;

        $properties = Property::where('is_active', true)
            ->where('status', 'available')
            ->with('tenant', 'propertyType', 'images')
            ->orderBy('name')
            ->get();

        $available = $properties->filter(function ($property) use ($checkIn, $checkOut) {
            $hasConflict = \App\Models\BookingItem::where('property_id', $property->id)
                ->whereHas('booking', function ($query) use ($checkIn, $checkOut) {
                    $query->whereNotIn('status', ['cancelled', 'completed'])
                        ->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                })
                ->exists();
            return !$hasConflict;
        });

        return $available->values();
    }

    public function getAvailableServicesProperty()
    {
        return Service::where('is_active', true)->orderBy('name')->get();
    }

    public function submit()
    {
        if (empty($this->selectedProperties)) {
            session()->flash('error', 'Please select at least one property.');
            return;
        }

        // Validate customer info if not logged in
        if (!Auth::check()) {
            $this->validate([
                'customerName' => 'required|string|max:255',
                'customerPhone' => 'nullable|string|max:20',
                'customerEmail' => 'required|email|max:255',
                'customerAddress' => 'nullable|string',
            ]);
        }

        $this->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Determine tenant_id (use first property's tenant as booking tenant)
        $firstPropertyId = array_key_first($this->selectedProperties);
        $firstProperty = Property::find($firstPropertyId);
        $tenantId = $firstProperty->tenant_id;

        // Create or retrieve customer
        if (Auth::check()) {
            $user = Auth::user();
            $customer = Customer::firstOrCreate(
                ['email' => $user->email, 'tenant_id' => $tenantId],
                ['name' => $user->name, 'phone' => $user->phone ?? null]
            );
        } else {
            $customer = Customer::firstOrCreate(
                ['email' => $this->customerEmail, 'tenant_id' => $tenantId],
                [
                    'name' => $this->customerName,
                    'phone' => $this->customerPhone,
                    'address' => $this->customerAddress,
                ]
            );
        }

        $booking = Booking::create([
            'tenant_id' => $tenantId,
            'customer_id' => $customer->id,
            'booking_reference' => 'BK-' . strtoupper(Str::random(8)),
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'total_amount' => $this->totalAmount,
            'status' => 'pending',
        ]);

        foreach ($this->selectedProperties as $propertyId => $item) {
            $property = Property::find($propertyId);
            BookingItem::create([
                'tenant_id' => $tenantId,
                'booking_id' => $booking->id,
                'property_id' => $propertyId,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'] * $this->nights,
            ]);
        }

        foreach ($this->selectedServices as $serviceId => $item) {
            $service = Service::find($serviceId);
            BookingService::create([
                'tenant_id' => $tenantId,
                'booking_id' => $booking->id,
                'service_id' => $serviceId,
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->flash('message', 'Booking request submitted! The business will confirm your reservation shortly.');
        return redirect()->route('home');
    }
};
?>

<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-6">Book Your Stay</h1>

        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Search Form & Available Properties --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Date Selection --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Select Dates</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Check-in</label>
                            <input type="date" wire:model.live="check_in" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Check-out</label>
                            <input type="date" wire:model.live="check_out" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 mt-2">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</p>
                </div>

                {{-- Available Properties --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Available Properties</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($this->availableProperties as $property)
                            <div class="border rounded-lg p-4 flex flex-col">
                                <div class="flex items-start gap-3">
                                    @if($property->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" class="h-16 w-16 object-cover rounded-lg">
                                    @else
                                        <div class="h-16 w-16 bg-slate-200 rounded-lg flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-medium text-slate-800">{{ $property->name }}</h3>
                                        <p class="text-sm text-slate-500">{{ $property->tenant->name ?? '' }}</p>
                                        <p class="text-sm text-slate-500">{{ $property->propertyType->name ?? '' }} · Up to {{ $property->capacity }} persons</p>
                                        <p class="text-blue-600 font-bold mt-1">₱{{ number_format($property->price, 2) }} / night</p>
                                    </div>
                                </div>
                                <button wire:click="addProperty({{ $property->id }}, {{ $property->price }})" class="mt-3 w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-1.5 rounded-lg text-sm transition">
                                    + Add to Booking
                                </button>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8 text-slate-500">
                                No properties available for selected dates.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column: Booking Summary --}}
            <div class="space-y-6">
                {{-- Selected Properties --}}
                @if(count($selectedProperties) > 0)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Selected Properties</h2>
                    <div class="space-y-3">
                        @foreach($selectedProperties as $id => $item)
                            @php $property = App\Models\Property::find($id); @endphp
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="font-medium">{{ $property->name ?? 'Property' }}</p>
                                    <p class="text-sm text-slate-500">₱{{ number_format($item['price'], 2) }} x {{ $item['quantity'] }} x {{ $nights }} nights</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number" wire:model.live="selectedProperties.{{ $id }}.quantity" min="1" class="w-14 border rounded text-center text-sm py-1">
                                    <button wire:click="removeProperty({{ $id }})" class="text-red-500">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Available Services --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Add Services</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($this->availableServices as $service)
                            <button wire:click="addService({{ $service->id }}, {{ $service->price }})" class="border rounded-full px-3 py-1.5 text-sm hover:bg-slate-50">
                                {{ $service->name }} (₱{{ number_format($service->price, 2) }})
                            </button>
                        @endforeach
                    </div>
                    @if(count($selectedServices) > 0)
                        <div class="space-y-2">
                            @foreach($selectedServices as $id => $item)
                                @php $service = App\Models\Service::find($id); @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <span>{{ $service->name }} x {{ $item['quantity'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <input type="number" wire:model.live="selectedServices.{{ $id }}.quantity" min="1" class="w-12 border rounded text-center text-sm py-1">
                                        <button wire:click="removeService({{ $id }})" class="text-red-500">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Customer Info (if not logged in) --}}
                @guest
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold mb-4">Your Information</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Full Name *</label>
                            <input type="text" wire:model="customerName" class="w-full rounded-lg border-slate-300">
                            @error('customerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email *</label>
                            <input type="email" wire:model="customerEmail" class="w-full rounded-lg border-slate-300">
                            @error('customerEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone</label>
                            <input type="text" wire:model="customerPhone" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Address</label>
                            <input type="text" wire:model="customerAddress" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>
                @endguest

                {{-- Total & Submit --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-2xl font-bold text-blue-600">₱{{ number_format($totalAmount, 2) }}</span>
                    </div>
                    <button wire:click="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg shadow-sm transition">
                        @auth
                            Confirm Booking
                        @else
                            Continue as Guest
                        @endauth
                    </button>
                    @guest
                        <p class="text-xs text-slate-400 mt-2 text-center">
                            You can also <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to book faster.
                        </p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>