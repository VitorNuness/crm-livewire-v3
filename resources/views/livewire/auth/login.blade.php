<x-card title="Login" shadow class="mx-auto w-[450px]">
    @if ($message = session()->get('status'))
        <x-alert :title="$message" icon="o-x-circle" class="alert-error my-4" />
    @endif

    <x-form wire:submit="tryToLogin">
        <x-input label="Email" wire:model="email" />
        <x-input label="Password" wire:model="password" type="password" />
    
        <x-slot:actions>
            <x-button label="I wanto to create an account" :link="route('auth.register')" class="btn-ghost" />
            <x-button label="Login" class="btn-primary" type="submit" spinner="tryToLogin" />
        </x-slot:actions>
        <x-button label="Forgot your password?" :link="route('password.recovery')" class="btn-ghost" />
    </x-form>
</x-card>
