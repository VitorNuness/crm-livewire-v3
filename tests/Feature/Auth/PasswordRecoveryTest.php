<?php

use App\Livewire\Auth\Password\Recovery;
use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\get;

test('needs to have a route to password recovery', function () {
    get(route('auth.password.recovery'))
        ->assertSeeLivewire(Recovery::class);
});

it('should be able to request for a password recovery sending notification to the user', function () {
    Notification::fake();
    $user = User::factory()->create();

    Livewire::test(Recovery::class)
        ->assertSeeHtml('alert-success')
        ->set('email', $user->email)
        ->call('startPasswordRecovery')
        ->assertSeeHtml('alert-success');

    Notification::assertSentTo($user, PasswordRecoveryNotification::class);
});

test('email validations', function ($value, $rule) {
    Livewire::test(Recovery::class)
        ->set('email', $value)
        ->call('startPasswordRecovery')
        ->assertHasErrors(['email' => $rule]);
})->with([
    'required' => ['value' => '', 'rule' => 'required'],
    'email'    => ['value' => 'email', 'rule' => 'email'],
]);
