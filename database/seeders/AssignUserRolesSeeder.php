<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Make the first user an admin if they don't have a role
        $firstUser = User::first();
        if ($firstUser && !$firstUser->role) {
            $firstUser->update(['role' => 'admin']);
        }

        // Assign 'user' role to all other users who don't have a role
        User::whereNull('role')->update(['role' => 'user']);
    }
}
