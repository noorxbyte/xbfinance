<?php

use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('transactions')->delete();

        // use the factory to create a Faker\Generator instance
		$faker = Faker\Factory::create();
        $transactions = [];

        for($i = 1; $i <= 20; $i++)
        {
        	array_push($transactions, [
        		'id' => $i,
        		'user_id' => 1,
        		'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        		'type' => 'DEPOSIT',
        		'account_id' => rand(1, 3),
        		'payee_id' => rand(1, 10),
        		'category_id' => rand(1, 20),
        		'amount' => rand(1000, 10000),
        		'comment' => $faker->sentence($nbWords = 10)
        	]);
        }

        for($i = 21; $i <= 220; $i++)
        {
        	array_push($transactions, [
        		'id' => $i,
        		'user_id' => 1,
        		'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        		'type' => 'WITHDRAWAL',
        		'account_id' => rand(1, 3),
        		'payee_id' => rand(1, 10),
        		'category_id' => rand(1, 5),
        		'amount' => rand(10, 500),
        		'comment' => $faker->sentence($nbWords = 10)
        	]);
        }
 
        // Uncomment the below to run the seeder
        DB::table('transactions')->insert($transactions);
    }
}
