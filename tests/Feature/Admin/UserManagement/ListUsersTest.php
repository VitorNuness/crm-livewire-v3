<?php

use App\Livewire\Admin\User\Index;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;

use function Pest\Laravel\{actingAs};

it('should be able to access the route admin/users', function () {
    $user = User::factory()->admin()->create();

    actingAs($user)
        ->get(route('admin.users'))
        ->assertOk();
});

test('make sure the route only access by admin users', function () {
    actingAs(User::factory()->create())
        ->getJson(route('admin.users'))
        ->assertForbidden();

    actingAs(User::factory()->admin()->create())
        ->get(route('admin.users'))
        ->assertOk();
});

test('can list all users', function () {
    $users = User::factory(10)->create();

    $livewire = Livewire::test(Index::class);

    $livewire->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($users as $user) {
        $livewire->assertSee($user->name);
    }
});
