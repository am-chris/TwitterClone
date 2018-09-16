<?php

use Carbon\Carbon;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $password = bcrypt('password');

        User::create([
            'username'     => 'ace',
            'name'         => 'Chris',
            'email'        => 'chris@app.com',
            'password'     => $password,
            'verified'     => 1,
            'created_at'   => Carbon::now(),
        ])->attachRole('admin');

        foreach (range(1, 5000) as $index) {
            $first_name = $faker->firstName;
            $full_name = $first_name . ' ' . $faker->lastName;
            $username = str_replace([' ', '-', '.'], '_', $faker->username) . rand(1, 999);

            $random_num = rand(0, 100);

            $verified = 0;
            if ($random_num >= 75) {
                $verified = 1;
            }

            $bio = null;
            if ($random_num >= 60) {
                $bio = $faker->sentence();
            }

            $private = 0;
            if ($random_num >= 90) {
                $private = 1;
            }

            $timestamp = Carbon::now();
            $users[] = [
                'email'        => $username . '@app.com',
                'password'     => $password,
                'username'     => $username,
                'name'         => $full_name,
                'bio'          => $bio,
                'private'      => $private,
                'verified'     => $verified,
                'created_at'   => $timestamp,
                'updated_at'   => $timestamp,
            ];
        }

        User::insert($users);
    }
}
