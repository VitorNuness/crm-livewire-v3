<x-card title="Register" shadow class="mx-auto w-[450px]">
    <x-form wire:submit="submit">
        <x-input label="Name" wire:model="name" />
        <x-input label="Email" wire:model="email" />
        <x-input label="Confirm your email" wire:model="email_confirmation" />
        <x-input label="Password" wire:model="password" type="password" />
    
        <x-slot:actions>
            <x-button label="I has an account" :link="route('login')" class="btn-ghost" />
            <x-button label="Cancel" type="reset" />
            <x-button label="Register" class="btn-primary" type="submit" spinner="submit" />
        </x-slot:actions>
    </x-form>
</x-card>
