<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\TypeOfTenant;
?>

<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Add Tenant Type</h1>
    <form wire:submit="save" class="space-y-4 bg-white p-6 rounded-xl shadow">
        <div>
            <label class="block text-sm font-medium mb-1">Type Name</label>
            <input type="text" wire:model="type" class="w-full rounded-lg border-slate-300">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Description (Optional)</label>
            <textarea wire:model="description" class="w-full rounded-lg border-slate-300"></textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg">Save</button>
            <a href="<?php echo e(route('superadmin.tenant-types.index')); ?>" class="px-6 py-2 border rounded-lg">Cancel</a>
        </div>
    </form>
</div><?php /**PATH C:\laragon\www\Capstone\storage\framework/views/livewire/views/1337d458.blade.php ENDPATH**/ ?>