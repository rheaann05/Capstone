@extends('tenant.layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Booking #{{ $booking->booking_reference }}</h1>
            <p class="text-slate-500">{{ $booking->customer->name }} • {{ $booking->check_in->format('M d, Y') }} – {{ $booking->check_out->format('M d, Y') }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tenant.payments.create', ['booking' => $booking->id]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Record Payment
            </a>
            <a href="{{ route('tenant.bookings.edit', $booking->id) }}" class="border px-4 py-2 rounded-lg hover:bg-slate-50">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Booking Items --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Booked Properties</h2>
                <table class="w-full text-sm">
                    <thead><tr class="text-slate-500"><th>Property</th><th>Price/Night</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($booking->items as $item)
                        <tr>
                            <td>{{ $item->property->name }}</td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Services --}}
            @if($booking->services->count())
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Additional Services</h2>
                <table class="w-full text-sm">
                    <thead><tr class="text-slate-500"><th>Service</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($booking->services as $service)
                        <tr>
                            <td>{{ $service->service->name }}</td>
                            <td>₱{{ number_format($service->service->price, 2) }}</td>
                            <td>{{ $service->quantity }}</td>
                            <td>₱{{ number_format($service->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Payments --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Payment History</h2>
                @if($booking->payments->count())
                <table class="w-full text-sm">
                    <thead><tr class="text-slate-500"><th>Date</th><th>Method</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($booking->payments as $payment)
                        <tr>
                            <td>{{ $payment->paid_at?->format('M d, Y') ?? $payment->created_at->format('M d, Y') }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs 
                                    {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-slate-500">No payments recorded yet.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            {{-- Summary --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Summary</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt>Total Amount</dt>
                        <dd class="font-medium">₱{{ number_format($booking->total_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Paid</dt>
                        <dd class="text-green-600">₱{{ number_format($booking->payments->where('payment_status', 'paid')->sum('amount'), 2) }}</dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt>Balance Due</dt>
                        <dd class="font-bold">₱{{ number_format($booking->total_amount - $booking->payments->where('payment_status', 'paid')->sum('amount'), 2) }}</dd>
                    </div>
                </dl>
                <div class="mt-4">
                    <span class="px-3 py-1 rounded-full text-sm 
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                           ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Customer</h2>
                <dl class="space-y-2 text-sm">
                    <div><dt class="text-slate-500">Name</dt><dd>{{ $booking->customer->name }}</dd></div>
                    @if($booking->customer->phone)
                    <div><dt class="text-slate-500">Phone</dt><dd>{{ $booking->customer->phone }}</dd></div>
                    @endif
                    @if($booking->customer->email)
                    <div><dt class="text-slate-500">Email</dt><dd>{{ $booking->customer->email }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection