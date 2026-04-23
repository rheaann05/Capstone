<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('tenant.layouts.app')]
#[Title('Record Payment')]
class extends Component {
    
    public Booking $booking;
    
    #[Validate('required|numeric|min:0.01|max:999999.99')]
    public $amount = 0;
    
    #[Validate('required|in:cash,card,gcash,paymaya,bank_transfer')]
    public $payment_method = 'cash';
    
    #[Validate('nullable|string|max:255')]
    public $reference_number = '';

    public function mount(Booking $booking)
    {
        if ($booking->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
        
        $this->booking = $booking;
        $paid = $booking->payments()->where('payment_status', 'paid')->sum('amount');
        $this->amount = max(0, $booking->total_amount - $paid);
    }

    public function processCashPayment()
    {
        $this->validate();

        Payment::create([
            'tenant_id' => Auth::user()->tenant_id,
            'booking_id' => $this->booking->id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status' => 'paid',
            'reference_number' => $this->reference_number,
            'paid_at' => now(),
        ]);

        session()->flash('message', 'Payment recorded successfully.');
        return $this->redirectRoute('tenant.bookings.show', $this->booking->id, navigate: true);
    }

    public function processOnlinePayment(PayMongoService $payMongo)
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:card,gcash,paymaya',
        ]);

        $customer = $this->booking->customer;
        
        $session = $payMongo->createCheckoutSession([
            'customer_name' => $customer->name,
            'customer_email' => $customer->email ?? 'guest@example.com',
            'customer_phone' => $customer->phone,
            'amount' => $this->amount,
            'description' => "Booking #{$this->booking->booking_reference}",
            'item_name' => 'Accommodation Payment',
            'success_url' => route('tenant.payments.success', ['booking' => $this->booking->id]),
            'cancel_url' => route('tenant.payments.cancel', ['booking' => $this->booking->id]),
            'metadata' => [
                'booking_id' => $this->booking->id,
                'tenant_id' => Auth::user()->tenant_id,
            ],
            'payment_method_types' => [$this->payment_method],
        ]);

        if (!$session) {
            session()->flash('error', 'Unable to initiate payment. Please try again.');
            return;
        }

        Payment::create([
            'tenant_id' => Auth::user()->tenant_id,
            'booking_id' => $this->booking->id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_status' => 'unpaid',
            'paymongo_session_id' => $session['data']['id'],
        ]);

        return redirect()->away($session['data']['attributes']['checkout_url']);
    }
};
?>
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Record Payment for Booking #{{ $booking->booking_reference }}</h1>
    
    <div class="bg-white rounded-xl shadow p-6">
        <div class="mb-4 p-3 bg-slate-50 rounded-lg">
            <p class="text-sm">Customer: {{ $booking->customer->name }}</p>
            <p class="text-sm">Total Amount: ₱{{ number_format($booking->total_amount, 2) }}</p>
            <p class="text-sm">Remaining Balance: ₱{{ number_format($amount, 2) }}</p>
        </div>

        <form wire:submit="{{ in_array($payment_method, ['cash', 'bank_transfer']) ? 'processCashPayment' : 'processOnlinePayment' }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Amount to Pay *</label>
                <input type="number" step="0.01" wire:model="amount" class="w-full rounded-lg border-slate-300">
                @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Payment Method *</label>
                <select wire:model.live="payment_method" class="w-full rounded-lg border-slate-300">
                    <option value="cash">Cash</option>
                    <option value="card">Credit/Debit Card (PayMongo)</option>
                    <option value="gcash">GCash (PayMongo)</option>
                    <option value="paymaya">PayMaya (PayMongo)</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
                @error('payment_method') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            @if(in_array($payment_method, ['cash', 'bank_transfer']))
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Reference Number (Optional)</label>
                <input type="text" wire:model="reference_number" class="w-full rounded-lg border-slate-300">
            </div>
            @endif

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    {{ in_array($payment_method, ['cash', 'bank_transfer']) ? 'Record Payment' : 'Proceed to Pay' }}
                </button>
                <a href="{{ route('tenant.bookings.show', $booking->id) }}" class="border px-6 py-2 rounded-lg hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
</div>