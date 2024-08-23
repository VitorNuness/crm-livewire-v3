<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\get;

test('need to receive a valid token with a combination with the email', function () {
    $user  = User::factory()->create();
    $token = Password::createToken($user);

    get(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]))->assertOk();

    get(route('password.reset', [
        'token' => 'token',
        'email' => 'email',
    ]))->assertRedirect(route('login'));
});
