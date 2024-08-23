<x-card title="Login" shadow class="mx-auto w-[450px]">
    <x-toast />

    <x-form wire:submit="tryToLogin">
        <x-input label="Email" wire:model="email" />
        <x-input label="Password" wire:model="password" type="password" />
    
        <x-slot:actions>
            <x-button label="I wanto to create an account" :link="route('auth.register')" class="btn-ghost" />
            <x-button label="Login" class="btn-primary" type="submit" spinner="tryToLogin" />
        </x-slot:actions>
        <x-button label="Forgot your password?" :link="route('auth.password.recovery')" class="btn-ghost" />
    </x-form>
</x-card>
