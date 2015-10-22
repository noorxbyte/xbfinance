<?php

use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
        DB::table('accounts')->delete();

        // use the factory to create a Faker\Generator instance
		$faker = Faker\Factory::create();
        $accounts = [];

        for($i = 1; $i <= 3; $i++)
        {
        	array_push($accounts, [
        		'id' => $i,
        		'user_id' => 1,
        		'name' => $faker->firstName()
        	]);
        }
 
        // Uncomment the below to run the seeder
        DB::table('accounts')->insert($accounts);
    }
}
