<?php

use App\Models\User;

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
