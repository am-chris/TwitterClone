<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\Following;

class FollowingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 10;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('following')->insert([ //,
                'user_id' =>  rand(1, 20),
                'follower_id' => rand(1, 20),
            ]);
        }
    }
}
