<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@email.com',
        'password' => '123456',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@email.com')
        ->set('password', '123456')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});

it('should make sure to inform the an error when email and password doesnt work', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@email.com')
        ->set('password', '123456')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials']);
});

it('should make sure that the rate limiting is blocking after 5 attempts', function () {
    for ($i = 5; $i > 0; $i--) {
        Livewire::test(Login::class)
            ->set('email', 'joe@email.com')
            ->set('password', '123456')
            ->call('tryToLogin')
            ->assertHasErrors(['invalidCredentials']);
    }

    Livewire::test(Login::class)
        ->set('email', 'joe@email.com')
        ->set('password', '123456')
        ->call('tryToLogin')
        ->assertHasErrors(['rateLimiter']);
});
