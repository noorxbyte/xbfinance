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
    public function show($id)
    {
        if ($id == 0)
        {
            // get transactions without payees
            $transactions = Transaction::where('user_id', Auth::user()->id)->whereNull('payee_id')->paginate(25);
        
            // stuff to pass into view
            $emptyMsg = "No transactions without payees.";
            $title = "Transactions by Payee";
            $heading = "Transactions Without Payees";
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
            $transactions = Payee::find($id)->transactions()->paginate(25);

            // stuff to pass into view
            $emptyMsg = "No transactions for this payee.";
            $title = "Payee Specific Transaction List";
            $heading = "Payee: " . Payee::find($id)->name;
        }

        return view('transactions.index', compact('transactions', 'emptyMsg', 'title', 'heading'));
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
