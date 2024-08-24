<?php

use App\Models\{Permission, User};

use function Pest\Laravel\assertDatabaseHas;

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
