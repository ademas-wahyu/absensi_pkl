<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register', function () {
    \Spatie\Permission\Models\Role::create(['name' => 'murid']);

    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'divisi' => 'IT',
        'sekolah' => 'SMK Tech',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'divisi' => 'IT',
        'sekolah' => 'SMK Tech',
    ]);

    $this->assertDatabaseHas('sekolahs', [
        'nama_sekolah' => 'SMK Tech',
    ]);
});
