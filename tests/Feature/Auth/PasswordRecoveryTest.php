<?php

use App\Livewire\PasswordRecovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('needs to have a route to password recovery', function () {
    get(route('password.recovery'))
        ->assertOk();
});

it('should be able to request for a password recovery sending notification to the user', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(PasswordRecovery::class)
        ->assertDontSee('You will receive an email with the password recovery link.')
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSee('You will receive an email with the password recovery link.');

    Notification::assertSentTo($user, PasswordRecoveryNotification::class);
});

test('email validations', function ($value, $rule) {
    Livewire::test(PasswordRecovery::class)
        ->set('email', $value)
        ->call('startPasswordRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'email', 'rule' => 'email'],
]);
