<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [

            // 👨‍🏫 HEAD TEACHER
            [
                'first_name' => 'Dotto',
                'middle_name' => 'T',
                'last_name' => 'Charles',
                'check_number' => '12345678',
                'email' => 'titto@gmail.com',
                'phone' => '0712345678',
                'sex' => 'male',
                'password' => Hash::make('12345678'),
                'role' => 'head_teacher',
                'status' => 'approved',
                'school_id' => 1,
                'ward_id' => 4,
            ],

            // 👩‍🏫 TEACHER 1
            [
                'first_name' => 'Teddy',
                'middle_name' => 'Dotto',
                'last_name' => 'Charles',
                'check_number' => '1234567',
                'email' => 'teacher1@school.com',
                'phone' => '0711111111',
                'sex' => 'female',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
                'status' => 'approved',
                'school_id' => 1,
                'ward_id' => 4,
            ],

            // 👨‍🏫 TEACHER 2
            [
                'first_name' => 'Peter',
                'middle_name' => 'J',
                'last_name' => 'Komba',
                'check_number' => 'T-0003',
                'email' => 'teacher2@school.com',
                'phone' => '0722222222',
                'sex' => 'male',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'status' => 'pending',
                'school_id' => null,
                'ward_id' => 4,
            ],

            // 🧑‍💼 WARD OFFICER
            [
                'first_name' => 'Neema',
                'middle_name' => 'K',
                'last_name' => 'Joseph',
                'check_number' => 'WO-0001',
                'email' => 'ward@system.com',
                'phone' => '0733333333',
                'sex' => 'female',
                'password' => Hash::make('password'),
                'role' => 'ward_officer',
                'status' => 'approved',
                'school_id' => null,
                'ward_id' => 4, // Dalai
            ],

            // 🏢 DISTRICT OFFICER
            [
                'first_name' => 'David',
                'middle_name' => 'L',
                'last_name' => 'Mrema',
                'check_number' => 'DO-0001',
                'email' => 'district@system.com',
                'phone' => '0744444444',
                'sex' => 'male',
                'password' => Hash::make('password'),
                'role' => 'district_officer',
                'status' => 'approved',
                'school_id' => null,
                'ward_id' => null,
            ],

            // 👨‍🏫 EXTRA TEACHER (TEST)
            [
                'first_name' => 'Dotto',
                'middle_name' => 'Titto',
                'last_name' => 'Charles',
                'check_number' => '12345678',
                'email' => 'tittocharles@gmail.com',
                'phone' => '0764409029',
                'sex' => 'male',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'approved',
                'school_id' => 1,
                'ward_id' => 4,
            ],

        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['check_number' => $user['check_number']],
                [
                    'first_name' => $user['first_name'],
                    'middle_name' => $user['middle_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'sex' => $user['sex'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                    'status' => $user['status'],
                    'school_id' => $user['school_id'],
                    'ward_id' => $user['ward_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}