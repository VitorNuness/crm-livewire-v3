<?php

namespace App\Livewire\Admin\User;

use App\Enums\ECan;
use App\Models\{Permission, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Livewire\Attributes\{Computed, Rule};
use Livewire\Component;

class Index extends Component
{
    public ?string $search = null;

    #[Rule('exists:permissions,id')]
    public array $search_permissions = [];

    #[Rule('bool')]
    public bool $search_trash = false;

    public Collection $permissionsSearchable;

    public function mount(): void
    {
        $this->authorize(ECan::BE_AN_ADMIN->value);
        $this->permissions();
    }

    public function render(): View
    {
        return view('livewire.admin.user.index');
    }

    public function permissions(): void
    {
        $this->permissionsSearchable = Permission::query()
            ->orderBy('key')
            ->get();
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate();

        return User::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q
                    ->where(
                        'name',
                        'like',
                        '%' . $this->search . '%'
                    )
                    ->orWhere(
                        'email',
                        'like',
                        '%' . $this->search . '%'
                    )
            )
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas(
                    'permissions',
                    fn (Builder $q) => $q->whereIn('id', $this->search_permissions)
                )
            )
            ->when(
                $this->search_trash,
                fn (Builder $q) => $q->onlyTrashed()
            )
            ->paginate();
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
