<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

new 
#[Layout('tenant.layouts.app')]
#[Title('Edit Employee')]
class extends Component {
    
    public Employee $employee;
    
    #[Validate('required|string|max:255')]
    public $name = '';
    
    #[Validate('nullable|string|max:50')]
    public $role = '';
    
    #[Validate('nullable|string|max:20')]
    public $phone = '';
    
    #[Validate('boolean')]
    public $is_active = true;

    public function mount(Employee $employee)
    {
        if ($employee->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }

        $this->employee = $employee;
        $this->name = $employee->name;
        $this->role = $employee->role;
        $this->phone = $employee->phone;
        // Force boolean cast to ensure checkbox reflects correctly
        $this->is_active = (bool) $employee->is_active;
    }

    public function update()
    {
        $this->validate();
        
        $this->employee->update([
            'name' => $this->name,
            'role' => $this->role,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ]);
        
        session()->flash('message', 'Employee updated successfully.');
        return $this->redirectRoute('tenant.employees.index', navigate: true);
    }
};
?>

<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>
    
    <form wire:submit="update" class="bg-white p-6 rounded-xl shadow space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Full Name *</label>
            <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Job Title / Role</label>
                <input type="text" wire:model="role" class="w-full rounded-lg border-slate-300">
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
        
        <div class="flex gap-3 pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2 data-loading:opacity-75">
                <span class="in-data-loading:hidden">Update Employee</span>
                <span class="not-in-data-loading:hidden">Saving...</span>
            </button>
            <a href="{{ route('tenant.employees.index') }}" wire:navigate class="border px-6 py-2 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        </div>
    </form>
</div>