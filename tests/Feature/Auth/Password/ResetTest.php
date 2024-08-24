<?php

use App\Livewire\Auth\Password\Reset;
use App\Models\User;
use Illuminate\Support\Facades\{Hash, Password};
use Livewire\Livewire;

use function Pest\Laravel\{get};

use function PHPUnit\Framework\assertTrue;

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

test('if is possible to reset the password with the given token', function () {
    $user  = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(Reset::class, [
        'token' => $token,
        'email' => $user->email,
    ])
        ->set('email_confirmation', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    $user->refresh();

    assertTrue(
        Hash::check('new-password', $user->password)
    );
});

test('validating form rules', function ($field, $value, $rule) {
    $user  = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(Reset::class, [
        'token' => $token,
        'email' => $user->email,
    ])
        ->set($field, $value)
        ->call('updatePassword')
        ->assertHasErrors([$field => $rule]);
})->with([
    'token::required'     => ['field' => 'token', 'value' => '', 'rule' => 'required'],
    'email::required'     => ['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::confirmed'    => ['field' => 'email', 'value' => 'email@email.com', 'rule' => 'confirmed'],
    'email::email'        => ['field' => 'email', 'value' => 'email', 'rule' => 'email'],
    'password::required'  => ['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::confirmed' => ['field' => 'password', 'value' => 'password', 'rule' => 'confirmed'],
    'password::max'       => ['field' => 'password', 'value' => str_repeat('a', 256), 'rule' => 'max'],
]);
