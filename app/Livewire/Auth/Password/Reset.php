<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Password};
use Livewire\Attributes\Layout;
use Livewire\Component;

class Reset extends Component
{
    public ?string $token = null;

    public ?string $email = null;

    public function mount(Request $request): void
    {
        $this->token = $request->get('token');
        $this->email = $request->get('email');

        if ($user = Password::getUser(['email' => $this->email])) {
            if (Password::tokenExists($user, $this->token)) {
                return;
            }
        }

        session()->flash('status', 'Invalid recovery token!');
        to_route('login');
    }

    #[Layout('components.layouts.guest')]
    public function render(): View
    {
        return view('livewire.auth.password.reset');
    }
}
