<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use App\Models\Tenant; 
use Illuminate\Support\Facades\Auth; // <-- Added Auth Facade import

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                if (!$user->hasRole('super-admin')) {
                    $model->tenant_id = $user->tenant_id;
                }
            }
            
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}