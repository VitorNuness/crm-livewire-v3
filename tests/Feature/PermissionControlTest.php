<?php

use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};

use function Pest\Laravel\{assertDatabaseHas, seed};

it('should be able to given an user a permission to do something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo('be an admin');

    expect($user)
        ->hasPermissionTo('be an admin')
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => 'be an admin',
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::query()->where('key', '=', 'be an admin')->first()->id,
    ]);
});

test('permission has to have a seeder', function () {
    seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => 'be an admin',
    ]);
});

test('seed with an admin user', function () {
    seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::query()->first()?->id,
        'permission_id' => Permission::query()->where(['key' => 'be an admin'])->first()?->id,
    ]);
});
