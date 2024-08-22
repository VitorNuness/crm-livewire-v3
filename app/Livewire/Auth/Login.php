<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Component;
use Mary\Traits\Toast;

class Login extends Component
{
    use Toast;

    public ?string $email = null;

    public ?string $password = null;

    public function render(): View
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.guest');
    }

    public function tryToLogin(): void
    {
        if ($this->enureIsNotRateLimited()) {
            return;
        }

        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ])) {
            RateLimiter::hit($this->throttleKey());
            $this->addError(
                'invalidCredentials',
                trans('auth.failed')
            );
            $this->error(trans('auth.failed'));

            return;
        }

        to_route('home');
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }

    private function enureIsNotRateLimited(): bool
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {

            $message = trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]);

            $this->addError('rateLimiter', $message);
            $this->warning($message);

            return true;
        }

        return false;
    }
}
