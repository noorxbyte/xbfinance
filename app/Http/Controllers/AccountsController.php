<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transfer;
use App\Account;
use App\User;
use Auth;
use DB;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get user's accounts
        $accounts = User::find(Auth::user()->id)->accounts;

        // stuff to pass into view
        $title = "Accounts";
        $heading = "Accounts";

        return view('accounts.index', compact('accounts', 'title', 'heading'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // stuff to pass into view
        $title = "New Account";
        $heading = "New Account";

        return view('accounts.create', compact('title', 'heading'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        $this->validate($request, [
            'name' => 'required|max:32',
        ]);

        // create a new account
        $account = new Account;

        // assign data
        $account->user_id = Auth::user()->id;
        $account->name = $request->name;

        // save the new record
        $account->save();

        // flash message
        session()->flash('flash_message', 'Account created successfully.');

        // redirect to accounts index
        return redirect()->route('accounts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $return
     * @id \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // check if the account exists
        $account = User::find(Auth::user()->id)->accounts->find($id);
        if ($account === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get the account's transactions
        $transactions = $account->transactions();

        // sort
        if (!empty($request->sort))
            $transactions = $transactions->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transactions = $transactions->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $title = "Transactions";
        $heading = $account['name'] . " - Transactions";

        $request->flash();

        return view('accounts.show', compact('transactions', 'heading', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // check if the account exists
        $account = User::find(Auth::user()->id)->accounts->find($id);
        if ($account === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // stuff to pass into view
        $title = "Edit Account";
        $heading = "Edit Account";

        return view('accounts.edit', compact('account', 'title', 'heading'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate request
        $this->validate($request, [
            'name' => 'required|max:32',
        ]);

        // check if the account exists
        $account = User::find(Auth::user()->id)->accounts->find($id);
        if ($account === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // update account name
        $account->name = $request->name;
        $account->save();

        // redirect to accounts
        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if account exists
        $account = Account::find($id);
        if ($account === null || $account->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // start database transaction
        DB::transaction(function () use ($account) {
            // delete account
            $account->delete();

            // get obsolete transfers
            $transfers = Transfer::whereNull('account_from')->whereNull('account_to')->get();

            // delete transfers
            foreach($transfers as $transfer)
                $transfer->delete();
        });

        // flash message
        session()->flash('flash_message', 'Account deleted successfully.');

        // redirect to accounts
        return redirect()->route('accounts.index');
    }
}
