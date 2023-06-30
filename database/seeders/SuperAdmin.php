<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $data = [
            [
                'name' => 'sadmin',
                'email' => 'sadmin@admin.com',
                'password' => '$2y$10$YSLGw0YS9e3V0B4RoXfBOuPhNseH9pVND5j7V2cpRK1wcr9IqML9W', // 'q1w2e3r4!@'
                'role' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        
        DB::table('users')->insert($data);
    }
}
