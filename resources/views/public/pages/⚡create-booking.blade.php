<?php

use Livewire\Component;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\BookingItem;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

new class extends Component {
    public Property $property;
    public $check_in;
    public $check_out;
    public $total_nights = 1;
    public $total_amount = 0;
    public $customerName = '';
    public $customerPhone = '';
    public $customerEmail = '';
    public $customerAddress = '';

    public function mount($property)
    {
        $this->property = Property::findOrFail($property);
        $this->check_in = now()->format('Y-m-d');
        $this->check_out = now()->addDay()->format('Y-m-d');
        $this->calculateTotal();
    }

    public function updatedCheckIn() { $this->calculateTotal(); }
    public function updatedCheckOut() { $this->calculateTotal(); }

    public function calculateTotal()
    {
        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);
        $this->total_nights = max(1, $checkIn->diffInDays($checkOut));
        $this->total_amount = $this->property->price * $this->total_nights;
    }

    public function save()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'nullable|string|max:20',
            'customerEmail' => 'nullable|email|max:255',
            'customerAddress' => 'nullable|string',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // 创建或获取客户
        $customer = Customer::firstOrCreate(
            ['email' => $this->customerEmail, 'tenant_id' => $this->property->tenant_id],
            [
                'name' => $this->customerName,
                'phone' => $this->customerPhone,
                'address' => $this->customerAddress,
                'tenant_id' => $this->property->tenant_id,
            ]
        );

        // 创建预订
        $booking = Booking::create([
            'tenant_id' => $this->property->tenant_id,
            'customer_id' => $customer->id,
            'booking_reference' => 'BK-' . strtoupper(Str::random(8)),
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'total_amount' => $this->total_amount,
            'status' => 'pending',
        ]);

        BookingItem::create([
            'tenant_id' => $this->property->tenant_id,
            'booking_id' => $booking->id,
            'property_id' => $this->property->id,
            'price' => $this->property->price,
            'quantity' => 1,
            'subtotal' => $this->total_amount,
        ]);

        session()->flash('message', 'Booking request submitted! The business will confirm your reservation shortly.');
        return redirect()->route('home');
    }

    public function render()
    {
        return view('public.pages.create-booking');
    }
};
?>

<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Complete Your Booking</h1>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
        <h2 class="font-semibold text-lg mb-3">{{ $property->name }}</h2>
        <p class="text-slate-600">{{ $property->propertyType->name ?? 'Property' }} · {{ $property->tenant->name }}</p>
        <p class="text-blue-600 font-bold mt-2">₱{{ number_format($property->price, 2) }} / night</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
        <div>
            <h3 class="font-medium text-slate-800 mb-3">Your Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name *</label>
                    <input type="text" wire:model="customerName" class="w-full rounded-lg border-slate-300">
                    @error('customerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" wire:model="customerPhone" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" wire:model="customerEmail" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <input type="text" wire:model="customerAddress" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <div>
            <h3 class="font-medium text-slate-800 mb-3">Stay Dates</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Check-in</label>
                    <input type="date" wire:model.live="check_in" class="w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Check-out</label>
                    <input type="date" wire:model.live="check_out" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <div class="border-t pt-4 flex justify-between items-center">
            <span class="text-lg font-bold">Total: ₱{{ number_format($total_amount, 2) }} ({{ $total_nights }} night{{ $total_nights > 1 ? 's' : '' }})</span>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition">
                Confirm Booking
            </button>
        </div>
    </form>
</div>