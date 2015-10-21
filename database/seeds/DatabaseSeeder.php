<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('ThemesSeeder');
        $this->call('UserSeeder');
        $this->call('AccountSeeder');
        $this->call('CategorySeeder');
        $this->call('PayeeSeeder');
        $this->call('TransactionSeeder');
        $this->call('TransferSeeder');
        $this->call('AccountBalance');

        Model::reguard();
    }
}
