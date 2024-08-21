<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

it('should be able to register a new user', function () {
    Livewire::test(Register::class)
        ->set('name', 'Joe Doe')
        ->set('email', 'joe@email.com')
        ->set('email_confirmation', 'joe@email.com')
        ->set('password', '12345678')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('home'));

    assertDatabaseHas('users', [
        'name'  => 'Joe Doe',
        'email' => 'joe@email.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())
        ->and(auth()->user())
        ->id->toBe(User::query()->first()->id);
});

test('validation rules', function ($object) {
    Livewire::test(Register::class)
        ->set($object->field, $object->value)
        ->call('submit')
        ->assertHasErrors([$object->field => $object->rule]);
})->with([
    'name::required'      => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max'           => (object)['field' => 'name', 'value' => str_repeat('a', 256), 'rule' => 'max'],
    'email::required'     => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::email'        => (object)['field' => 'email', 'value' => 'email', 'rule' => 'email'],
    'email::max'          => (object)['field' => 'email', 'value' => str_repeat('a' . '@email.com', 256), 'rule' => 'max'],
    'email::confirmed'    => (object)['field' => 'email', 'value' => 'email', 'rule' => 'confirmed'],
    'password::required ' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::max'       => (object)['field' => 'password', 'value' => str_repeat('a', 256), 'rule' => 'max'],
]);
