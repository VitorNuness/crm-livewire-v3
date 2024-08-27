<?php

namespace App\Livewire\Admin;

use App\Enums\ECan;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(): void
    {
        $this->authorize(ECan::BE_AN_ADMIN->value);
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }
}
