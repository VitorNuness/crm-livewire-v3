<?php

namespace App\Providers;

use App\Enums\ECan;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach (ECan::cases() as $can) {
            Gate::define(
                $can->value,
                fn (User $user) => $user->hasPermissionTo($can)
            );
        }
    }
}
