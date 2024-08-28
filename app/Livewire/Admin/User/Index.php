<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        return view('livewire.admin.user.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::query()->paginate();
    }
}
