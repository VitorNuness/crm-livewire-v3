<?php

namespace App\Livewire\Admin\User;

use App\Enums\ECan;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public function mount(): void
    {
        $this->authorize(ECan::BE_AN_ADMIN->value);
    }

    public function render(): View
    {
        return view('livewire.admin.user.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }
}
