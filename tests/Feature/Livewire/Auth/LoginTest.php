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
