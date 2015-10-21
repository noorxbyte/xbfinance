<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('categories')->delete();

        // use the factory to create a Faker\Generator instance
		$faker = Faker\Factory::create();
        $categories = [];

        for($i = 1; $i <= 20; $i++)
        {
        	array_push($categories, [
        		'id' => $i,
        		'user_id' => 1,
        		'name' => $faker->word()
        	]);
        }
 
        // Uncomment the below to run the seeder
        DB::table('categories')->insert($categories);
    }
}
