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

        // limit user to max 5 accounts
        if (User::find(Auth::user()->id)->accounts->count() >= 5)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "You have reached your maximum account limit.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

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

        // validate request
        $this->validate($request, [
            'sort' => 'in:date,amount',
            'order' => 'in:DESC,ASC',
            'type' => 'in:WITHDRAWAL,DEPOSIT'
        ]);

        // get the account's transactions
        $transactions = $account->transactions();

        // filter
        if (!empty($request->type))
            $transactions = $transactions->where('type', $request->type);

        // remember total records
        session()->flash('total_count', ceil($transactions->count() / 25));

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

    /**
     * Display search results
     *
     * @return \Illuminate\Http\Response
     */
    public function search($id, Request $request)
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

        // validate request
        $this->validate($request, [
            'sort' => 'in:date,amount',
            'order' => 'in:DESC,ASC',
            'type' => 'in:WITHDRAWAL,DEPOSIT'
        ]);

        // get a list of all transactions
        $transactions = $account->transactions()->where('comment', 'LIKE', '%' . $request->q . '%');

        // filter
        if (!empty($request->type))
            $transactions = $transactions->where('type', $request->type);

        // remember total records
        session()->flash('total_count', ceil($transactions->count() / 25));

        // sort
        if (!empty($request->sort))
            $transactions = $transactions->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transactions = $transactions->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $action = ['AccountsController@search', $id];
        $emptyMsg = "No Results for '" . $request->q . "'";
        $title = "Search Transactions";
        $heading = "Search Transactions - '" . $request->q ."'";

        $request->flash();
        
        return view('transactions.index', compact('transactions', 'action', 'emptyMsg', 'title', 'heading'));
    }
}
