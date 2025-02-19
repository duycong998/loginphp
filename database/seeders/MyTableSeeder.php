<?php

namespace Database\Seeders;

use App\Models\MyTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class MyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MyTable::create([
            'name' => 'Duy Cong Nguyen Luong',
            'email' => 'dc998@example.com',
            'phone' => '0123456789',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);
    }
}
