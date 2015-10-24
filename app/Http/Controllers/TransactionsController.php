<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use App\User;
use Auth;
use DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get user's transactions
        $transactions = Transaction::where('user_id', Auth::user()->id);

        // remember total records
        session()->flash('total_count', ceil($transactions->count() / 25));

        // sort
        if (!empty($request->sort))
            $transactions = $transactions->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transactions = $transactions->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $action = "TransactionsController@index";
        $title = "All Transactions";
        $heading = "All Transactions";

        $request->flash();

        return view('transactions.index', compact('transactions', 'action', 'title', 'heading'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get user's accounts
        $accounts = User::find(Auth::user()->id)->accounts->lists('name', 'id')->all();

        // get user's payees
        $payees = User::find(Auth::user()->id)->payees->lists('name', 'id')->all();

        // get user's categories
        $categories = User::find(Auth::user()->id)->categories->lists('name', 'id')->all();

        // stuff to pass into view
        $title = "New Transaction";
        $heading = "New Transaction";

        return view('transactions.create', compact('transaction', 'accounts', 'payees', 'categories', 'title', 'heading'));
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
            'date' => 'required|date_format:d/m/Y',
            'type' => 'required|in:WITHDRAWAL,DEPOSIT',
            'account_id' => 'required|integer|min:1',
            'payee_id' => 'required|integer|min:0',
            'category_id' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'max:255'
        ]);

        // check if account exists
        $account = User::find(Auth::user()->id)->accounts->find($request->account_id);
        if ($account === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get the amount and balance
        $balance = $account->balance * 100;
        $amount = round(($request->amount * 100), 2);

        // check if payee exists
        if ($request->payee_id != 0)
        {
            $payee = User::find(Auth::user()->id)->payees->find($request->payee_id);
            if ($payee === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The payee does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // check if category exists
        if ($request->category_id != 0)
        {
            $category = User::find(Auth::user()->id)->categories->find($request->category_id);
            if ($category === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The category does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // start new transaction
        DB::transaction(function () use ($request, $balance, $amount) {
            // create new transaction record
            $transaction = new Transaction;

            // format date
            $date = date('Y-m-d', (strtotime(str_replace('/', '-', $request->date))));

            // set the values
            $transaction->user_id = Auth::user()->id;
            $transaction->type = $request->type;
            $transaction->date = $date;
            $transaction->account_id = $request->account_id;
            if ($request->payee_id != 0)
                $transaction->payee_id = $request->payee_id;
            if ($request->category_id != 0)
                $transaction->category_id = $request->category_id;
            $transaction->amount = round($request->amount, 2);
            $transaction->comment = $request->comment;

            // save the transaction
            $transaction->save();

            // get the account record
            $account = User::find(Auth::user()->id)->accounts->find($request->account_id);

            // update the values
            if (strcasecmp($request->type, "WITHDRAWAL") == 0)
            {
                $account->balance = round((($balance - $amount) / 100), 2);
            }
            else if (strcasecmp($request->type, "DEPOSIT") == 0)
            {
                $account->balance = round((($balance + $amount) / 100), 2);
            }

            // update the balance
            $account->save();
        });

        // flash message
        session()->flash('flash_message', 'Transaction created successfully.');

        // redirect to transactions list
        return redirect()->route('accounts.show', $request->account_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // check if transaction exists
        $transaction = Transaction::find($id);
        if (($transaction === null) || ($transaction->account->user->id != Auth::user()->id))
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transaction does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // format transaction's date
        $transaction->date = new \DateTime($transaction->date);
        $transaction->date = $transaction->date->format('d/m/Y');

        // get user's accounts
        $accounts = User::find(Auth::user()->id)->accounts->lists('name', 'id')->all();

        // get user's payees
        $payees = User::find(Auth::user()->id)->payees->lists('name', 'id')->all();

        // get user's categories
        $categories = User::find(Auth::user()->id)->categories->lists('name', 'id')->all();

        // stuff to pass into view
        $title = "Edit Transaction";
        $heading = "Edit Transaction";

        return view('transactions.edit', compact('transaction', 'accounts', 'payees', 'categories', 'title', 'heading'));
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
            'date' => 'required|date_format:d/m/Y',
            'type' => 'required|in:WITHDRAWAL,DEPOSIT',
            'account_id' => 'required|integer|min:1',
            'payee_id' => 'required|integer|min:0',
            'category_id' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'max:255'
        ]);

        // check if transaction exists
        $transaction = Transaction::find($id);;
        if (($transaction === null) || ($transaction->account->user->id != Auth::user()->id))
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transaction does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // remember old account id
        $old_account_id = $transaction->account->id;

        // check if account exists
        $account = User::find(Auth::user()->id)->accounts->find($request->account_id);
        if ($account === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get the amount and balance
        $balance = $account->balance * 100;
        $amount = round(($request->amount * 100), 2);

        // check if payee exists
        if ($request->payee_id != 0)
        {
            $payee = User::find(Auth::user()->id)->payees->find($request->payee_id);
            if ($payee === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The payee does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // check if category exists
        if ($request->category_id != 0)
        {
            $category = User::find(Auth::user()->id)->categories->find($request->category_id);
            if ($category === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The category does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // start transaction
        DB::transaction(function () use ($id, $request, $balance, $amount, $old_account_id, $transaction) {
            /***** revert balance in old account *****/

            // get the old account
            $account = User::find(Auth::user()->id)->accounts->find($old_account_id);
            $transaction = Transaction::find($id);

            //dd(round(((($account->balance * 100) + ($transaction->amount * 100)) / 100), 2));

            if (strcasecmp($transaction->type, "WITHDRAWAL") == 0)
                $account->balance = round(((($account->balance * 100) + ($transaction->amount * 100)) / 100), 2);
            if (strcasecmp($transaction->type, "DEPOSIT") == 0)
                $account->balance = round(((($account->balance * 100) - ($transaction->amount * 100)) / 100), 2);

            $account->save();

            /***** ----- *****/

            /***** update transaction *****/

            // format date
            $date = date('Y-m-d', (strtotime(str_replace('/', '-', $request->date))));

            $transaction->type = $request->type;
            $transaction->date = $date;
            $transaction->account_id = $request->account_id;
            if ($request->payee_id != 0)
                $transaction->payee_id = $request->payee_id;
            else
                $transaction->payee_id = null;
            if ($request->category_id != 0)
                $transaction->category_id = $request->category_id;
            else
                $transaction->category_id = null;
            $transaction->amount = round($request->amount, 2);
            $transaction->comment = $request->comment;

            $transaction->save();

            /***** ----- *****/

            /***** update account balance *****/

            $account = User::find(Auth::user()->id)->accounts->find($request->account_id);

            if (strcasecmp($request->type, "WITHDRAWAL") == 0)
                $account->balance = round(((($account->balance * 100) - $amount) / 100), 2);
            if (strcasecmp($request->type, "DEPOSIT") == 0)
                $account->balance = round(((($account->balance * 100) + $amount) / 100), 2);

            $account->save();  

            /***** ----- *****/
        });

        // flash message
        session()->flash('flash_message', 'Transaction updated successfully.');

        // redirect to transactions list
        return redirect()->route('accounts.show', $request->account_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if transaction exists
        $transaction = Transaction::find($id);
        if ($transaction === null || $transaction->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transaction does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get account of transaction
        $account = $transaction->account;

        // get balance and amount
        $balance = $transaction->account->balance * 100;
        $amount = $transaction->amount * 100;

        // get final balance
        if (strcasecmp($transaction->type, "DEPOSIT") == 0)
            $balance = $balance - $amount;
        if (strcasecmp($transaction->type, "WITHDRAWAL") == 0)
            $balance = $balance + $amount;

        // start database transaction
        DB::transaction(function () use ($transaction, $account, $balance) {
            // delete transaction
            $transaction->delete();

            // update balance
            $account->balance = round($balance, 2);
            $account->save();
        });

        // redirect to transactions
        return redirect()->route('transactions.index');
    }

    /**
     * Display search results
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // get a list of all transactions
        $transactions = Transaction::where('user_id', Auth::user()->id)->where('comment', 'LIKE', '%' . $request->q . '%');

        // remember total records
        session()->flash('total_count', ceil($transactions->count() / 25));

        // sort
        if (!empty($request->sort))
            $transactions = $transactions->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transactions = $transactions->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $action = 'TransactionsController@search';
        $emptyMsg = "No Results for '" . $request->q . "'";
        $title = "Search Transactions";
        $heading = "Search Transactions - '" . $request->q ."'";

        $request->flash();
        
        return view('transactions.index', compact('transactions', 'action', 'emptyMsg', 'title', 'heading'));
    }
}
