<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Employee;
use App\Models\User;
use App\Models\TenantSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

new 
#[Layout('tenant.layouts.app')]
#[Title('Create Employee')]
class extends Component {
    
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('nullable|string|max:50')]
    public $employeeRole = ''; // free‑text job title (display only)
    
    #[Validate('nullable|string|max:20')]
    public $phone = '';
    
    #[Validate('boolean')]
    public $is_active = true;
    
    // Optional user account
    public $create_user = false;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRole = '';

    public function getAvailableRolesProperty()
    {
        $roles = collect();

        // Global Spatie roles (tenant‑assignable)
        $globalRoles = Role::whereNotIn('name', ['super-admin', 'admin'])
            ->orderBy('name')
            ->get()
            ->map(fn($role) => [
                'type'        => 'global',
                'value'       => $role->name,
                'label'       => ucfirst($role->name) . ' (Global)',
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);

        $roles = $roles->concat($globalRoles);

        // Tenant custom roles from tenant_settings
        $setting = TenantSetting::where('tenant_id', Auth::user()->tenant_id)
            ->where('key', 'custom_roles')
            ->first();
        $customRoles = $setting ? $setting->value : [];

        foreach ($customRoles as $index => $customRole) {
            $roles->push([
                'type'        => 'custom',
                'value'       => 'custom_' . $index,
                'label'       => $customRole['name'] . ' (Custom)',
                'permissions' => $customRole['permissions'],
            ]);
        }

        return $roles;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'employeeRole' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ];

        if ($this->create_user) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ];
            $rules['password'] = 'required|min:8|confirmed';
            $rules['selectedRole'] = 'required';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $userId = null;

            if ($this->create_user) {
                $user = User::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'name'      => $this->name,
                    'email'     => $this->email,
                    'password'  => Hash::make($this->password),
                    'is_active' => true,
                ]);

                $selected = $this->availableRoles->firstWhere('value', $this->selectedRole);
                if ($selected) {
                    if ($selected['type'] === 'global') {
                        $user->assignRole($selected['value']);
                    } else {
                        $user->syncPermissions($selected['permissions']);
                    }
                }

                $userId = $user->id;
            }

            Employee::create([
                'tenant_id' => Auth::user()->tenant_id,
                'user_id'   => $userId,
                'name'      => $this->name,
                'role'      => $this->employeeRole,
                'phone'     => $this->phone,
                'is_active' => $this->is_active,
            ]);
        });

        session()->flash('message', 'Employee created successfully.');
        return $this->redirectRoute('tenant.employees.index', navigate: true);
    }
};
?>

<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Add Employee</h1>
    
    <form wire:submit="save" class="bg-white p-6 rounded-xl shadow space-y-4">
        {{-- Basic Info --}}
        <div>
            <label class="block text-sm font-medium mb-1">Full Name *</label>
            <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Job Title / Role</label>
                <input type="text" wire:model="employeeRole" placeholder="e.g. Receptionist, Housekeeping" class="w-full rounded-lg border-slate-300">
                <p class="text-xs text-slate-400 mt-1">Display only; does not affect permissions.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" wire:model="phone" class="w-full rounded-lg border-slate-300">
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300">
            <label class="text-sm">Active</label>
        </div>
        
        {{-- User Account Toggle --}}
        <div class="border-t pt-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model.live="create_user">
                <span class="font-medium">Create user account for this employee</span>
            </label>
            
            @if($create_user)
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" wire:model="email" class="w-full rounded-lg border-slate-300">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Password *</label>
                            <input type="password" wire:model="password" class="w-full rounded-lg border-slate-300">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Confirm Password *</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Assign System Role *</label>
                        <select wire:model="selectedRole" class="w-full rounded-lg border-slate-300">
                            <option value="">-- Select Role --</option>
                            @foreach($this->availableRoles as $role)
                                <option value="{{ $role['value'] }}">{{ $role['label'] }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-slate-400 mt-1">Determines what the employee can access in the system.</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Form Actions --}}
        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2 data-loading:opacity-75">
                <span class="in-data-loading:hidden">Save Employee</span>
                <span class="not-in-data-loading:hidden">Saving...</span>
            </button>
            <a href="{{ route('tenant.employees.index') }}" wire:navigate class="border px-6 py-2 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        </div>
    </form>
</div>