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

test('only be an admin users can see the admin items in the menu', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('home'))
        ->assertDontSeeHtml('<summary @click.prevent="toggle()" class="hover:text-inherit text-inherit"><!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]--><svg class="inline w-5 h-5 inline-flex" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"></path></svg><!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]--><span class="mary-hideable">Admin</span></summary>');
});
