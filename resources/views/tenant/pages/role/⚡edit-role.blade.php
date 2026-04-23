<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\TenantSetting;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

new 
#[Layout('tenant.layouts.app')]
#[Title('Edit Custom Role')]
class extends Component {
    
    public int $index;
    
    #[Validate]
    public $name = '';
    
    public $selectedPermissions = [];
    public $customRoles = [];

    public function mount($index)
    {
        $this->index = (int) $index;
        $this->loadCustomRoles();
        
        if (!isset($this->customRoles[$this->index])) {
            abort(404, 'Custom role not found.');
        }
        
        $role = $this->customRoles[$this->index];
        $this->name = $role['name'];
        $this->selectedPermissions = $role['permissions'];
    }

    protected function loadCustomRoles()
    {
        $setting = TenantSetting::where('tenant_id', Auth::user()->tenant_id)
            ->where('key', 'custom_roles')
            ->first();
        $this->customRoles = $setting ? $setting->value : [];
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    foreach ($this->customRoles as $i => $role) {
                        if ($i != $this->index && strtolower($role['name']) === strtolower($value)) {
                            $fail('A custom role with this name already exists.');
                            return;
                        }
                    }
                },
                Rule::notIn(['super-admin', 'admin']),
            ],
            'selectedPermissions' => 'array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'selectedPermissions.min' => 'Please select at least one permission.',
            'name.not_in' => 'The role name ":input" is reserved and cannot be used.',
        ];
    }

    public function getAvailablePermissionsProperty()
    {
        $excludePatterns = [
            'delete%',
            '%user%',
            'role%',
            'permission%',
            '%super-admin%',
            '%admin%',
            'tenant%',
            'platform%',
        ];

        $query = Permission::orderBy('name');
        
        foreach ($excludePatterns as $pattern) {
            $query->where('name', 'not like', $pattern);
        }

        return $query->get();
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $this->customRoles[$this->index] = [
                'name' => $this->name,
                'permissions' => $this->selectedPermissions,
            ];

            TenantSetting::updateOrCreate(
                ['tenant_id' => Auth::user()->tenant_id, 'key' => 'custom_roles'],
                ['value' => $this->customRoles]
            );
        });

        session()->flash('message', 'Custom role updated successfully.');
        return $this->redirectRoute('tenant.roles.index', navigate: true);
    }
};
?>

<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Custom Role</h1>
    
    <form wire:submit="update" class="bg-white p-6 rounded-xl shadow space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Role Name *</label>
            <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            <p class="text-xs text-slate-400 mt-1">Cannot use "admin" or "super-admin".</p>
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium">Assign Permissions *</label>
                <span class="text-xs text-slate-500">{{ count($selectedPermissions) }} selected</span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 max-h-80 overflow-y-auto border rounded-lg p-3">
                @forelse($this->availablePermissions as $permission)
                    <label class="flex items-center gap-2 text-sm hover:bg-slate-50 p-1 rounded">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="rounded border-slate-300">
                        {{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}
                    </label>
                @empty
                    <p class="text-slate-500 col-span-2 text-center py-4">No assignable permissions available.</p>
                @endforelse
            </div>
            @error('selectedPermissions') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2 data-loading:opacity-75">
                <span class="in-data-loading:hidden">Update Role</span>
                <span class="not-in-data-loading:hidden">Saving...</span>
            </button>
            <a href="{{ route('tenant.roles.index') }}" wire:navigate class="border px-6 py-2 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        </div>
    </form>
</div>