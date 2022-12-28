<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //KETIGA DATA INI AKAN DIJADIKAN DUMMY USER DENGAN MASING-MASING ROLE YANG DIMILIKINYA
        User::create([
            'name' => 'Anugrah Sandi',
            'email' => 'nuge@gmail.com',
            'password' => bcrypt('secret'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Riski Amelia',
            'email' => 'riski@gmail.com',
            'password' => bcrypt('secret'),
            'role' => 'manager'
        ]);

        User::create([
            'name' => 'DaengWeb',
            'email' => 'daengweb@gmail.com',
            'password' => bcrypt('secret'),
            'role' => 'user'
        ]);
    }
}
