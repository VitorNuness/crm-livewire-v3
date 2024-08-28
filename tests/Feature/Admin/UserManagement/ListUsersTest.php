<?php

use App\Livewire\Admin\User\Index;
use App\Models\{Permission, User};
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

    actingAs(User::factory()->admin()->create());
    $livewire = Livewire::test(Index::class);
    $livewire->assertSet('users', function ($users) {
        expect($users)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(11);

        return true;
    });

    foreach ($users as $user) {
        $livewire->assertSee($user->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->admin()->create());
    Livewire::test(Index::class)
        ->assertSet('headers', [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ]);
});

it('should be able to filter by name and email', function () {
    $users = User::factory(10)->create(['name' => 'Zzz']);
    $admin = User::factory()->admin()->create(['name' => 'Admin', 'email' => 'admin@email.com']);

    actingAs($admin);
    $livewire = Livewire::test(Index::class)
        ->set('search', 'adm');

    $livewire->assertSee($admin->name);

    foreach ($users as $user) {
        $livewire->assertDontSee($user->name);
    }
});

it('should be able to filter by permission key', function () {
    $users      = User::factory(10)->create(['name' => 'Zzz']);
    $admin      = User::factory()->admin()->create(['name' => 'Admin', 'email' => 'admin@email.com']);
    $permission = Permission::query()->first();

    actingAs($admin);
    $livewire = Livewire::test(Index::class)
        ->set('search_permissions', [$permission->id]);

    $livewire->assertSee($admin->name);

    foreach ($users as $user) {
        $livewire->assertDontSee($user->name);
    }
});
