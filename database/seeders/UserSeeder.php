<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ GET FIRST SCHOOL SAFELY
        $school = School::first();

        // If no school exists → stop
        if (!$school) {
            $this->command->error('No school found. Please seed schools first.');
            return;
        }

        // =============================
        // 👨‍🏫 HEAD TEACHER
        // =============================
        $headTeacher = User::create([
            'first_name'   => 'John',
            'middle_name'  => 'M',
            'last_name'    => 'Mwalimu',
            'check_number' => 'HT-0001',
            'email'        => 'head@school.com',
            'phone'        => '0712345678',
            'sex'          => 'male',
            'password'     => Hash::make('password123'),
            'role'         => 'head_teacher',
            'status'       => 'approved',
            'school_id'    => $school->id,
        ]);

        // =============================
        // 👩‍🏫 TEACHER 1
        // =============================
        User::create([
            'first_name'   => 'Asha',
            'middle_name'  => 'S',
            'last_name'    => 'Ali',
            'check_number' => 'T-0002',
            'email'        => 'teacher1@school.com',
            'phone'        => '0711111111',
            'sex'          => 'female',
            'password'     => Hash::make('password123'),
            'role'         => 'teacher',
            'status'       => 'approved',
            'school_id'    => $school->id,
        ]);

        // =============================
        // 👨‍🏫 TEACHER 2 (PENDING)
        // =============================
        User::create([
            'first_name'   => 'Peter',
            'middle_name'  => 'J',
            'last_name'    => 'Komba',
            'check_number' => 'T-0003',
            'email'        => 'teacher2@school.com',
            'phone'        => '0722222222',
            'sex'          => 'male',
            'password'     => Hash::make('password123'),
            'role'         => 'teacher',
            'status'       => 'pending',
            'school_id'    => $school->id,
        ]);

        // =============================
        // 🏢 WARD OFFICER
        // =============================
        User::create([
            'first_name'   => 'Neema',
            'middle_name'  => 'K',
            'last_name'    => 'Joseph',
            'check_number' => 'WO-0001',
            'email'        => 'ward@system.com',
            'phone'        => '0733333333',
            'sex'          => 'female',
            'password'     => Hash::make('password123'),
            'role'         => 'ward_officer',
            'status'       => 'approved',
            'school_id'    => null,
        ]);

        // =============================
        // 🏛 DISTRICT OFFICER
        // =============================
        User::create([
            'first_name'   => 'David',
            'middle_name'  => 'L',
            'last_name'    => 'Mrema',
            'check_number' => 'DO-0001',
            'email'        => 'district@system.com',
            'phone'        => '0744444444',
            'sex'          => 'male',
            'password'     => Hash::make('password123'),
            'role'         => 'district_officer',
            'status'       => 'approved',
            'school_id'    => null,
        ]);

        // =============================
        // 🎯 ASSIGN HEAD TEACHER TO SCHOOL
        // =============================
    
    }
}