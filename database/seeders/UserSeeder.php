<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Database\Seeders\Factories;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::query()->truncate();
        $user = (User::class)->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $user->assignRole('SuperAdmin');
    }
}
