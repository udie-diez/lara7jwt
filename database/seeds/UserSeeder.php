<?php

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 20; $i++) {
            $gender = $faker->randomElement(['female', 'male']);

            DB::table('users')->insert([
                'name' => $faker->name($gender),
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'phone_number' => $faker->unique()->phoneNumber,
                'gender' => substr($gender, 0, 1)
            ]);
        }
    }
}
