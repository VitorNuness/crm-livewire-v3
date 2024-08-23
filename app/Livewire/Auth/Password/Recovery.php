<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Recovery extends Component
{
    use Toast;

    public ?string $message = null;

    #[Rule(['required', 'email'])]
    public ?string $email = null;

    public function render(): View
    {
        return view('livewire.auth.password.recovery')
            ->layout('components.layouts.guest');
    }

    public function startPasswordRecovery(): void
    {
        $this->validate();

        if ($user = User::query()->whereEmail($this->email)->first()) {
            $user->notify(new PasswordRecoveryNotification());
        }

        $this->message = 'You will receive an email with the password recovery link.';
        $this->success($this->message);
    }
}
