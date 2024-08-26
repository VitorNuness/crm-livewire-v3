<?php

use App\Enums\ECan;
use App\Models\{Permission, User};
use Database\Seeders\{PermissionSeeder, UserSeeder};
use Illuminate\Support\Facades\{Cache, DB};

use function Pest\Laravel\{actingAs, assertDatabaseHas, seed};

it('should be able to given an user a permission to do something', function () {
    $user = User::factory()->create();

    $user->givePermissionTo(ECan::BE_AN_ADMIN);

    expect($user)
        ->hasPermissionTo(ECan::BE_AN_ADMIN)
        ->toBeTrue();

    assertDatabaseHas('permissions', [
        'key' => ECan::BE_AN_ADMIN->value,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => $user->id,
        'permission_id' => Permission::query()->where('key', '=', ECan::BE_AN_ADMIN->value)->first()->id,
    ]);
});

test('permission must have a seeder', function () {
    seed(PermissionSeeder::class);

    assertDatabaseHas('permissions', [
        'key' => ECan::BE_AN_ADMIN->value,
    ]);
});

test('seed with an admin user', function () {
    seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);

    assertDatabaseHas('permission_user', [
        'user_id'       => User::query()->first()?->id,
        'permission_id' => Permission::query()->where(['key' => ECan::BE_AN_ADMIN->value])->first()?->id,
    ]);
});

it('should block the access to admin pages of the user not have the permission to be an admin', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('make sure that we are using cache to store user permissions', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(ECan::BE_AN_ADMIN);

    $cacheKey = 'user::' . $user->id . '::permissions';

    expect(Cache::has($cacheKey))
        ->toBeTrue()
        ->and(Cache::get($cacheKey))
        ->toBe($user->permissions);
});

test('make sure that we are using the cache to retrieve/check when the user has the given permissions', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(ECan::BE_AN_ADMIN);

    $hit = false;
    DB::listen(function ($query) use (&$hit) {
        $hit = true;
    });
    $user->hasPermissionTo(ECan::BE_AN_ADMIN);

    expect($hit)->toBeFalse();
});
