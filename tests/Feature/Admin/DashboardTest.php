<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('block access to users without the permission be an admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    Livewire::test(Dashboard::class)
        ->assertForbidden();
});
