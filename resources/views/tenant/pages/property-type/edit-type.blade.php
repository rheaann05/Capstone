<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

new 
#[Layout('tenant.layouts.app')]
#[Title('Edit Property')]
class extends Component {
    use WithFileUploads;

    public Property $property;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:property_types,id')]
    public $property_type_id = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|integer|min:1')]
    public $capacity = 1;

    #[Validate('required|numeric|min:0|max:99999999.99')]
    public $price = 0.00;

    #[Validate('required|in:available,occupied,maintenance')]
    public $status = 'available';

    #[Validate('boolean')]
    public $is_active = true;

    // For new image uploads
    public $newImages = [];
    public $existingImages = [];

    public function mount(Property $property)
    {
        // Ensure property belongs to current tenant
        if ($property->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }

        $this->property = $property;
        $this->name = $property->name;
        $this->property_type_id = $property->property_type_id;
        $this->description = $property->description;
        $this->capacity = $property->capacity;
        $this->price = $property->price;
        $this->status = $property->status;
        // 👇 Cast to boolean so checkbox reflects correctly
        $this->is_active = (bool) $property->is_active;

        // Load existing images
        $this->existingImages = $property->images->map(function ($image) {
            return [
                'id' => $image->id,
                'path' => $image->image_path,
                'url' => Storage::url($image->image_path),
            ];
        })->toArray();
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('properties', 'name')
                    ->where('tenant_id', Auth::user()->tenant_id)
                    ->ignore($this->property->id),
            ],
            'property_type_id' => 'required|exists:property_types,id',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:available,occupied,maintenance',
            'is_active' => 'boolean',
            'newImages.*' => 'nullable|image|max:5120', // 5MB max per image
        ];
    }

    public function removeExistingImage($imageId)
    {
        $image = PropertyImage::find($imageId);
        if ($image && $image->property_id === $this->property->id) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            
            // Refresh existing images list
            $this->existingImages = $this->property->fresh()->images->map(function ($img) {
                return [
                    'id' => $img->id,
                    'path' => $img->image_path,
                    'url' => Storage::url($img->image_path),
                ];
            })->toArray();
        }
    }

    public function removeNewImage($index)
    {
        if (isset($this->newImages[$index])) {
            unset($this->newImages[$index]);
            $this->newImages = array_values($this->newImages);
        }
    }

    public function update()
    {
        $this->validate();

        $this->property->update([
            'name' => trim($this->name),
            'property_type_id' => $this->property_type_id,
            'description' => trim($this->description),
            'capacity' => $this->capacity,
            'price' => $this->price,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ]);

        // Handle new image uploads
        foreach ($this->newImages as $image) {
            $path = $image->store('property-images', 'public');
            PropertyImage::create([
                'tenant_id' => Auth::user()->tenant_id,
                'property_id' => $this->property->id,
                'image_path' => $path,
            ]);
        }

        session()->flash('message', 'Property updated successfully.');
        return $this->redirectRoute('tenant.properties.index', navigate: true);
    }

    public function getAvailablePropertyTypesProperty()
    {
        return PropertyType::availableForTenant(Auth::user()->tenant_id)
            ->orderBy('name')
            ->get();
    }
};
?>

<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Property</h1>
        <p class="text-slate-500">Update details for {{ $property->name }}</p>
    </div>

    <form wire:submit="update" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
        {{-- Basic Information --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Property Name *</label>
                <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300 focus:ring-blue-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Property Type *</label>
                <select wire:model="property_type_id" class="w-full rounded-lg border-slate-300 focus:ring-blue-500">
                    <option value="">-- Select Type --</option>
                    @foreach($this->availablePropertyTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('property_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
            <textarea wire:model="description" rows="3" class="w-full rounded-lg border-slate-300 focus:ring-blue-500"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Capacity *</label>
                <input type="number" wire:model="capacity" min="1" class="w-full rounded-lg border-slate-300 focus:ring-blue-500">
                @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Price per Night *</label>
                <input type="number" step="0.01" wire:model="price" class="w-full rounded-lg border-slate-300 focus:ring-blue-500">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
                <select wire:model="status" class="w-full rounded-lg border-slate-300 focus:ring-blue-500">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Active Toggle --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_active" class="rounded border-slate-300">
            <label class="text-sm">Active (visible to customers)</label>
        </div>

        {{-- Existing Images --}}
        @if(count($existingImages) > 0)
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Current Images</label>
            <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($existingImages as $image)
                    <div class="relative group">
                        <img src="{{ $image['url'] }}" class="w-full h-24 object-cover rounded-lg border">
                        <button type="button" wire:click="removeExistingImage({{ $image['id'] }})" 
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- New Images Upload --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Add New Images</label>
            <input type="file" wire:model="newImages" multiple accept="image/*" class="w-full">
            @error('newImages.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            @if(count($newImages) > 0)
                <div class="grid grid-cols-3 md:grid-cols-4 gap-3 mt-3">
                    @foreach($newImages as $index => $image)
                        <div class="relative group">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-24 object-cover rounded-lg border">
                            <button type="button" wire:click="removeNewImage({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-4 border-t">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2 data-loading:opacity-75">
                <span class="in-data-loading:hidden">Update Property</span>
                <span class="not-in-data-loading:hidden">Saving...</span>
            </button>
            <a href="{{ route('tenant.properties.index') }}" wire:navigate class="border px-6 py-2 rounded-lg hover:bg-slate-50 transition">Cancel</a>
        </div>
    </form>
</div>