<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use App\Payee;
use App\User;
use Auth;

class PayeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get user's payees
        $payees = Payee::with('transactions')->where('user_id', Auth::user()->id)->get();

        // get all transactions of the user
        $transactions = Transaction::where('user_id', Auth::user()->id)->get();

        // calculate total income
        $incomeTotal = $transactions->where('type', 'DEPOSIT')->sum('amount');
        $expenseTotal = $transactions->where('type', 'WITHDRAWAL')->sum('amount');

        // get uncategorized transactions
        $transactions = Transaction::where('user_id', Auth::user()->id)->whereNull('payee_id')->get();

        // stuff to pass into view
        $title = "Payees";
        $heading = "Payees";

        return view('payees.index', compact('payees', 'transactions', 'incomeTotal', 'expenseTotal', 'title', 'heading'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // stuff to pass into view
        $title = "New Payee";
        $heading = "New Payee";

        return view('payees.create', compact('title', 'heading'));
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
            'name' => 'required|max:32'
        ]);

        // prevent duplicate payees
        if (User::find(Auth::user()->id)->payees()->whereRaw("UPPER(`name`) = UPPER(?)", array($request->name))->count() > 0)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "Payee with name already exists.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // create new record and save it
        $payee = new Payee;
        $payee->user_id = Auth::user()->id;
        $payee->name = $request->name;
        $payee->save();

        // flash message
        session()->flash('flash_message', 'Payee created successfully.');

        // redirect to payees
        return redirect()->route('payees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if ($id == 0)
        {
            // get transactions without payees
            $transactions = User::find(Auth::user()->id)->transactions()->whereNull('payee_id');

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
        }
        else
        {
            // check if payee exists
            $payee = User::find(Auth::user()->id)->payees->find($id);
            if ($payee === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The payee does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }

            // get the transactions of the payee
            $transactions = Payee::find($id)->transactions();

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
        }

        // stuff to pass into view
        $action = ['PayeesController@show', $id];
        $emptyMsg = "No transactions for this payee.";
        $title = "Payee Specific Transaction List";
        if ($id != 0)
            $heading = "Payee: " . Payee::find($id)->name;
        else
            $heading = "Payee-less Transactions";

        $request->flash();

        return view('transactions.index', compact('transactions', 'action', 'emptyMsg', 'title', 'heading'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get payee data
        $payee = User::find(Auth::user()->id)->payees->find($id);
        if ($payee === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The payee does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // stuff to pass into view
        $title = "Edit Payee";
        $heading = "Edit Payee";

        return view('payees.edit', compact('payee', 'title', 'heading'));
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
            'name' => 'required|max:32'
        ]);

        // check if payee exists
        $payee = User::find(Auth::user()->id)->payees->find($id);
        if ($payee === null)
        {
            // stuff to pass into view
            $title = "Warning";
            $errtype = "warning";
            $errmsg = "The payee does not exist.";

            return view('errors.error', compact('errtype', 'errmsg', 'title', 'heading'));
        }

        // prevent duplicate payees
        if (User::find(Auth::user()->id)->payees()->whereRaw("UPPER(`name`) = UPPER(?)", array($request->name))->count() > 0)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "Payee with name already exists.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // update the payee
        $payee->name = $request->name;
        $payee->save();

        // flash message
        session()->flash('flash_message', 'Payee updated successfully.');

        // redirect to payees
        return redirect()->route('payees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if payee exists
        $payee = Payee::find($id);
        if ($payee === null || $payee->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The payee does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // delete payee
        $payee->delete();

        // flash message
        session()->flash('flash_message', 'Payee deleted successfully.');

        // redirect to payees
        return redirect()->route('payees.index');
    }
}
