<?php

use Carbon\Carbon;
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

        DB::table('users')->insert([
            'username'     => 'ace',
            'name'         => 'Chris',
            'email'        => 'chris@app.com',
            'password'     => 'password',
            'verified'     => 1,
            'created_at'   => Carbon::now(),
        ]);

        foreach (range(1, 100) as $index) {
            $first_name = $faker->firstName;
            $full_name = $first_name . ' ' . $faker->lastName;
            $username = str_replace([' ', '-', '.'], '_', $faker->username) . rand(1, 99);

            $random_num = rand(0, 100);

            $verified = 0;
            if ($random_num >= 75) {
                $verified = 1;
            }

            $bio = null;
            if ($random_num >= 60) {
                $bio = $faker->sentence();
            }

            $timestamp = Carbon::now();
            $users[] = [
                'email'        => $username . '@app.com',
                'password'     => $password,
                'username'     => $username,
                'name'         => $full_name,
                'bio'          => $bio,
                'verified'     => $verified,
                'created_at'   => $timestamp,
                'updated_at'   => $timestamp,
            ];
        }

        User::insert($users);
    }
}
