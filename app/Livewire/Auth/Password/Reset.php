<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Password};
use Illuminate\Support\Str;
use Livewire\Attributes\{Computed, Layout, Rule};
use Livewire\Component;
use Mary\Traits\Toast;

class Reset extends Component
{
    use Toast;

    #[Rule(['required'])]
    public ?string $token = null;

    #[Rule(['required', 'email', 'confirmed'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required', 'max:255', 'confirmed'])]
    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(Request $request): void
    {
        $this->token = $request->get('token', $this->token);
        $this->email = $request->get('email', $this->email);

        if ($this->tokenNotValid()) {
            session()->flash('status', trans('passwords.token'));
            to_route('login');
        }
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }

    public function updatePassword(): void
    {
        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        $status !== Password::PASSWORD_RESET ?
            $this->error(trans($status)) :
            $this->success(trans($status), redirectTo: route('login'));
    }

    #[Computed]
    public function obfuscatedEmail(): string
    {
        return obfuscate_email($this->email);
    }

    private function tokenNotValid(): bool
    {
        $user = Password::getUser(['email' => $this->email]);

        if (empty($user)) {
            return true;
        } else {
            return !Password::tokenExists($user, $this->token);
        }
    }
}
