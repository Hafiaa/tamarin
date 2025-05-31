<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user {email=admin@example.com} {password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->warn('User with this email already exists!');
            return 1;
        }

        // Create admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        // Assign admin role if using spatie/laravel-permission
        if (class_exists('Spatie\Permission\Models\Role')) {
            $user->assignRole('admin');
        }

        $this->info('Admin user created successfully!');
        $this->line('Email: ' . $email);
        $this->line('Password: ' . $password);

        return 0;
    }
}
