<x-card title="Password reset" shadow class="mx-auto w-[450px]">
    <x-form wire:submit="updatePassword">
        <x-input label="Email" name="email" :value="$this->obfuscatedEmail" readonly />
        <x-input label="Confirm your email" wire:model="email_confirmation" />
        <x-input label="New password" wire:model="password" type="password" />
        <x-input label="Confirm your new password" wire:model="password_confirmation" type="password" />
    
        <x-slot:actions>
            <x-button label="Never mind, get back to login page." :link="route('login')" class="btn-ghost" />
            <x-button label="Confirm" class="btn-primary" type="submit" spinner="updatePassword" />
        </x-slot:actions>
    </x-form>
</x-card>
