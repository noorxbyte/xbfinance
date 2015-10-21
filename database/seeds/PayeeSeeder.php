<?php

use Illuminate\Database\Seeder;

class PayeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('payees')->delete();

        // use the factory to create a Faker\Generator instance
		$faker = Faker\Factory::create();
        $payees = [];

        for($i = 1; $i <= 10; $i++)
        {
        	array_push($payees, [
        		'id' => $i,
        		'user_id' => 1,
        		'name' => $faker->company()
        	]);
        }
 
        // Uncomment the below to run the seeder
        DB::table('payees')->insert($payees);
    }
}
