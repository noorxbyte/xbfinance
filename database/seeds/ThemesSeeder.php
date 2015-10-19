<?php

use Illuminate\Database\Seeder;

class ThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('themes')->delete();
 
        $themes = array(
            ['name' => 'default', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['name' => 'yeti', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['name' => 'slate', 'created_at' => new DateTime, 'updated_at' => new DateTime],
        );
 
        // Uncomment the below to run the seeder
        DB::table('themes')->insert($themes);
    }
}
