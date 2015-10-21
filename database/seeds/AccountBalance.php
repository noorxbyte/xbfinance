<?php

use Illuminate\Database\Seeder;

class AccountBalance extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get a list of all user account's
        $accounts = App\Account::get();

        // calculate the balance of each account
        foreach($accounts as $account)
        {
            $account->balance += App\Account::find($account->id)->transactions->where('type', 'DEPOSIT')->sum('amount');
            $account->balance -= App\Account::find($account->id)->transactions->where('type', 'WITHDRAWAL')->sum('amount');

            $account->balance -= App\Transfer::where('account_from', $account->id)->sum('amount');
            $account->balance += App\Transfer::where('account_to', $account->id)->sum('amount');
        
            $account->save();
        }
    }
}
