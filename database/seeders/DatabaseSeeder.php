<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Wedding Organizer',
            'email' => 'admin@wo.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Customer User
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@wo.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Categories
        // $categories = [
        //     ['name' => 'Intimate Wedding', 'slug' => 'intimate-wedding', 'description' => 'Perfect for small, close-knit family gatherings.'],
        //     ['name' => 'Luxury Wedding', 'slug' => 'luxury-wedding', 'description' => 'Premium and lavish wedding package for a grand celebration.'],
        //     ['name' => 'Outdoor Wedding', 'slug' => 'outdoor-wedding', 'description' => 'Beautiful outdoor setup with natural scenery.'],
        // ];

    }
}
