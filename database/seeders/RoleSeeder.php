<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::findOrCreate('super_admin');
        Role::findOrCreate('mentor');
        Role::findOrCreate('mentee');

        $admin = User::role('super_admin')->first();
        $admin->assignRole(['super_admin', 'mentor']);
    }
}
