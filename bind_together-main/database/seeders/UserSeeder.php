<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map roles to their corresponding email addresses
        $roleEmails = [
            UserTypeEnum::SUPERADMIN => 'superadmin@bpsu.edu.ph',
            UserTypeEnum::ADMINSPORT => 'adminsport@bpsu.edu.ph',
            UserTypeEnum::ADMINORG => 'adminorg@bpsu.edu.ph',
            UserTypeEnum::COACH => 'coach@bpsu.edu.ph',
            UserTypeEnum::ADVISER => 'adviser@bpsu.edu.ph',
            UserTypeEnum::STUDENT => 'student@bpsu.edu.ph',
        ];

        foreach ($roleEmails as $roleName => $email) {
            $user = User::factory()->create([
                'email' => $email,
                'password' => Hash::make('password'),
            ]);

            // Assign the role to the user
            $user->assignRole($roleName);
        }
    }
}
