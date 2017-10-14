<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\User;

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

        $limit = 20;

        DB::table('users')->insert([ //,
            'username' => 'ace',
            'name' => 'Chris L',
            'email' => 'chris@app.com',
            'password' => bcrypt('password'),
            'verified' => 1,
            'created_at' => Carbon::now(),
        ]);

        for ($i = 0; $i < $limit; $i++) {
            $first_name = $faker->firstName;
            $full_name = $first_name . ' ' . $faker->lastName;

            // Make 25% of users verified
            $random_num = rand(0,99);

            if ($random_num >= 75) {
                $verified = 1;
            } else {
                $verified = 0;
            }

            if ($random_num >= 60) {
                $bio = $faker->sentence();
            } else {
                $bio = null;
            }

            DB::table('users')->insert([
                'username' => str_replace([' ', '-', '.'], '_', $faker->username),
                'name' => $full_name,
                'email' =>  $first_name . '@app.com',
                'password' => bcrypt('password'),
                'bio' => $bio,
                'verified' => $verified,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
