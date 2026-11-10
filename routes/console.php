<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('logbook:create-admin {name} {email}', function (string $name, string $email) {
    $password = $this->secret('Administrator password (minimum 12 characters)');
    if (strlen((string) $password) < 12) { $this->error('Password must contain at least 12 characters.'); return 1; }
    \App\Models\User::updateOrCreate(['email'=>$email], ['name'=>$name,'password'=>\Illuminate\Support\Facades\Hash::make($password)]);
    $this->info('Administrator account saved.');
})->purpose('Create or update an administrator account');
