<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transfer;
use App\User;
use Auth;
use DB;

class TransfersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get a list of all transfers
        $transfers = Transfer::where('user_id', Auth::user()->id);

        // remember total records
        session()->flash('total_count', ceil($transfers->count() / 25));

        // sort
        if (!empty($request->sort))
            $transfers = $transfers->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transfers = $transfers->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $action = 'TransfersController@index';
        $title = "Transfers";
        $heading = "Transfers";

        $request->flash();
        
        return view('transfers.index', compact('transfers', 'action', 'title', 'heading'));
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

        // stuff to pass into view
        $title = "New Transfer";
        $heading = "New Transfer";

        return view('transfers.create', compact('accounts', 'title', 'heading'));
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
            'account_from' => 'required|integer|min:1|different:account_to',
            'account_to' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'max:255'
        ]);

        // check if accounts exists
        $account_from = User::find(Auth::user()->id)->accounts->find($request->account_from);
        $account_to = User::find(Auth::user()->id)->accounts->find($request->account_to);
        if ($account_from === null || $account_to === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The account does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // start new transaction
        DB::transaction(function () use ($request, $account_from, $account_to) {
            // create new transfer record
            $transfer = new Transfer;

            // format date
            $date = date('Y-m-d', (strtotime(str_replace('/', '-', $request->date))));

            // assign data
            $transfer->user_id = Auth::user()->id;
            $transfer->date = $date;
            $transfer->account_from = $request->account_from;
            $transfer->account_to = $request->account_to;
            $transfer->amount = $request->amount;
            $transfer->comment = $request->comment;

            // save transfer
            $transfer->save();

            // update acounts balances
            $account_from = User::find(Auth::user()->id)->accounts->find($request->account_from);
            $account_to = User::find(Auth::user()->id)->accounts->find($request->account_to);

            $account_from->balance = round(((($account_from->balance * 100) - ($request->amount * 100)) / 100), 2);
            $account_to->balance = round(((($account_to->balance * 100) + ($request->amount * 100)) / 100), 2);

            $account_from->save();
            $account_to->save();
        });

        // flash message
        session()->flash('flash_message', 'Transfer executed successfull.');

        // redirect to transfers list
        return redirect()->route('transfers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the transfer record
        $transfer = Transfer::find($id);
        if ($transfer === null || $transfer->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transfer does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get user's accounts
        $accounts = User::find(Auth::user()->id)->accounts->lists('name', 'id')->all();

        // format transfer's date
        $transfer->date = new \DateTime($transfer->date);
        $transfer->date = $transfer->date->format('d/m/Y');

        // stuff to pass into view
        $title = "Edit Transfer";
        $heading = "Edit Transfer";

        return view('transfers.edit', compact('transfer', 'accounts', 'title', 'heading'));
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
            'account_from' => 'required|integer|min:0|different:account_to',
            'account_to' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'max:255'
        ]);

        // check if both account's null
        if ($request->account_from == 0 && $request->account_to == 0)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "Cannot create an empty transfer.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        $account_from = null;
        $account_to = null;

        if ($request->account_from != 0)
        {
            // check if account exists
            $account_from = User::find(Auth::user()->id)->accounts->find($request->account_from);
            if ($account_from === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The account does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        if ($request->account_to != 0)
        {
            // check if account exists
            $account_to = User::find(Auth::user()->id)->accounts->find($request->account_to);
            if ($account_to === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The account does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // check if transfer exists
        $transfer = Transfer::find($id);
        if ($transfer === null || $transfer->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transfer does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // previous accounts
        $account_from = $transfer->from;
        $account_to = $transfer->to;

        // start new transaction
        DB::transaction(function () use ($request, $transfer, $account_from, $account_to) {
            // roll back transfer balances
            if ($account_from !== null)
                $account_from->balance = round(((($account_from->balance * 100) + ($transfer->amount * 100)) / 100), 2);
            if ($account_to !== null)
                $account_to->balance = round(((($account_to->balance * 100) - ($transfer->amount * 100)) / 100), 2);
            
            if ($account_from !== null)
                $account_from->save();
            if ($account_to !== null)
                $account_to->save();

            // format date
            $date = date('Y-m-d', (strtotime(str_replace('/', '-', $request->date))));

            // assign data
            $transfer->date = $date;
            if ($request->account_from == 0)
                $transfer->account_from = null;
            else
                $transfer->account_from = $request->account_from;
            if ($request->account_to == 0)
                $transfer->account_to = null;
            else
                $transfer->account_to = $request->account_to;
            $transfer->amount = round($request->amount, 2);
            $transfer->comment = $request->comment;

            // save transfer
            $transfer->save();

            // update acounts balances
            $account_from = User::find(Auth::user()->id)->accounts->find($request->account_from);
            $account_to = User::find(Auth::user()->id)->accounts->find($request->account_to);

            if ($account_from !== null)
                $account_from->balance = round(((($account_from->balance * 100) - ($request->amount * 100)) / 100), 2);
            if ($account_to !== null)
                $account_to->balance = round(((($account_to->balance * 100) + ($request->amount * 100)) / 100), 2);

            if ($account_from !== null)
                $account_from->save();
            if ($account_to !== null)
                $account_to->save();
        });

        // flash message
        session()->flash('flash_message', 'Transfer edited successfully.');

        // redirect to transfers list
        return redirect()->route('transfers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if transfer exists
        $transfer = Transfer::find($id);
        if ($transfer === null || $transfer->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The transfer does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // get the account's for updating
        $account_from = $transfer->from;
        $account_to = $transfer->to;

        // start new transaction
        DB::transaction(function () use ($transfer, $account_from, $account_to) {
            // update account balances
            if ($account_from !== null)
                $account_from->balance = round(((($account_from->balance * 100) + ($transfer->amount * 100)) / 100), 2);
            if ($account_to !== null)
                $account_to->balance = round(((($account_to->balance * 100) - ($transfer->amount * 100)) / 100), 2);

            if ($account_from !== null)
                $account_from->save();
            if ($account_to !== null)
                $account_to->save();

            // delete transfer
            $transfer->delete();
        });

        // flash message
        session()->flash('flash_message', 'Transfer deleted successfully. Balances rolled back!');

        // redirect to transfers
        return redirect()->route('transfers.index');
    }

    /**
     * Display search results
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // get a list of all transfers
        $transfers = Transfer::where('user_id', Auth::user()->id)->where('comment', 'LIKE', '%' . $request->q . '%');

        // remember total records
        session()->flash('total_count', ceil($transfers->count() / 25));

        // sort
        if (!empty($request->sort))
            $transfers = $transfers->orderBy($request->sort, $request->order)->simplePaginate(25);
        else
            $transfers = $transfers->orderBy('date', 'desc')->simplePaginate(25);

        // stuff to pass into view
        $action = 'TransfersController@search';
        $emptyMsg = "No Results for '" . $request->q . "'";
        $title = "Search Transfers";
        $heading = "Search Transfers - '" . $request->q ."'";

        $request->flash();
        
        return view('transfers.index', compact('transfers', 'action', 'emptyMsg', 'title', 'heading'));
    }
}
