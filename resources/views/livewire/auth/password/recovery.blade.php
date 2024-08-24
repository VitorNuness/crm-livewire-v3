<x-card title="Password Recovery" shadow class="mx-auto w-[450px]">
    <x-toast />

    <x-form wire:submit="startPasswordRecovery">
        <x-input label="Email" wire:model="email" />
    
        <x-slot:actions>
            <x-button label="Never mind, get back to login page." :link="route('login')" class="btn-ghost" />
            <x-button label="Send mail" class="btn-primary" type="submit" spinner="startPasswordRecovery" />
        </x-slot:actions>
    </x-form>
</x-card>
