<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Carbon\Carbon;

class Booking extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'booking_reference',
        'check_in',
        'check_out',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function getCheckInAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getCheckOutAttribute($value): ?Carbon
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(BookingItem::class);
    }

    public function services()
    {
        return $this->hasMany(BookingService::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    protected static function booted()
    {
        static::updated(function (Booking $booking) {
            if (in_array($booking->status, ['completed', 'cancelled'])) {
             
                $propertyIds = $booking->items()->pluck('property_id')->unique();
                Property::whereIn('id', $propertyIds)->update(['status' => 'available']);
            }
        });
    }
}