<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('users')->delete();
 
        $users = array(
            ['id' => 1, 'name' => 'Hussain Noor Mohamed', 'email' => 'noor.xbyte@gmail.com', 'password' => bcrypt('123456'), 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'name' => 'Demo User', 'email' => 'demo@user.com', 'password' => bcrypt('123456'), 'created_at' => new DateTime, 'updated_at' => new DateTime],
        );
 
        // Uncomment the below to run the seeder
        DB::table('users')->insert($users);
    }
}
