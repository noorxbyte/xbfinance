<?php

use Illuminate\Database\Seeder;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('transfers')->delete();

        // use the factory to create a Faker\Generator instance
		$faker = Faker\Factory::create();
        $transfers = [];

        for($i = 1; $i <= 50; $i++)
        {
            $num1 = rand(1, 3);
            $num2 = rand(1, 3);

            while ($num1 == $num2)
            {
                $num1 = rand(1, 3);
                $num2 = rand(1, 3);
            }

        	array_push($transfers, [
        		'id' => $i,
        		'user_id' => 1,
        		'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        		'account_from' => $num1,
        		'account_to' => $num2,
        		'amount' => rand(1, 100),
        		'comment' => $faker->sentence($nbWords = 10)
        	]);
        }
 
        // Uncomment the below to run the seeder
        DB::table('transfers')->insert($transfers);
    }
}
