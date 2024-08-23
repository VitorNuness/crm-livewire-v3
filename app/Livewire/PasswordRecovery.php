<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PasswordRecovery extends Component
{
    public ?string $message = null;

    public ?string $email = null;

    public function render(): View
    {
        return view('livewire.password-recovery');
    }

    public function startPasswordRecovery(): void
    {
        if ($user = User::query()->whereEmail($this->email)->first()) {
            $user->notify(new PasswordRecoveryNotification());
        }

        $this->message = 'You will receive an email with the password recovery link.';
    }
}
