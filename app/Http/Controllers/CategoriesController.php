<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use App\Category;
use App\User;
use Auth;
use DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get user's categories
        $categories = Category::with('transactions')->where('user_id', Auth::user()->id)->get();

        // get all transactions of the user
        $transactions = Transaction::where('user_id', Auth::user()->id)->get();

        // calculate total income and total expense
        $incomeTotal = $transactions->where('type', 'DEPOSIT')->sum('amount');
        $expenseTotal = $transactions->where('type', 'WITHDRAWAL')->sum('amount');

        // get uncategorized transactions
        $transactions = Transaction::where('user_id', Auth::user()->id)->whereNull('category_id')->get();

        // stuff to pass into view
        $title = "Categories";
        $heading = "Categories";

        return view('categories.index', compact('categories', 'transactions', 'incomeTotal', 'expenseTotal', 'title', 'heading'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // stuff to pass into view
        $title = "New Category";
        $heading = "New Category";

        return view('categories.create', compact('title', 'heading'));
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

        // prevent duplicate categories
        $categories = User::find(Auth::user()->id)->categories;
        foreach ($categories as $category)
        {
            if (strcasecmp($category->name, $request->name) == 0)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "Category with name already exists.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // create new record and save it
        $category = new Category;
        $category->user_id = Auth::user()->id;
        $category->name = $request->name;
        $category->save();

        // flash message
        session()->flash('flash_message', 'Category created successfully.');

        // redirect to categories
        return redirect()->route('categories.index');
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
            // get uncategorized transactions
            $transactions = User::find(Auth::user()->id)->transactions()->whereNull('category_id');

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
            // check if category exists
            $category = User::find(Auth::user()->id)->categories->find($id);
            if ($category === null)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "The category does not exist.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }

            // get the transactions of the category
            $transactions = Category::find($id)->transactions();

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
        $action = ["CategoriesController@show", $id];
        $emptyMsg = "No transactions for this category.";
        $title = "Category Specific Transaction List";
        if ($id != 0)
            $heading = "Category: " . Category::find($id)->name;
        else
            $heading = "Category: Uncategorized";

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
        // get category data
        $category = User::find(Auth::user()->id)->categories->find($id);
        if ($category === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The category does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // stuff to pass into view
        $title = "Edit Category";
        $heading = "Edit Category";

        return view('categories.edit', compact('category', 'title', 'heading'));
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

        // check if category exists
        $category = User::find(Auth::user()->id)->categories->find($id);
        if ($category === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The category does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // prevent duplicate categories
        $categories = User::find(Auth::user()->id)->categories;
        foreach ($categories as $category)
        {
            if (strcasecmp($category->name, $request->name) == 0)
            {
                // stuff to pass into view
                $title = "Error";
                $errmsg = "Category with name already exists.";

                return view('errors.error', compact('errmsg', 'title', 'heading'));
            }
        }

        // update the category
        $category->name = $request->name;
        $category->save();

        // flash message
        session()->flash('flash_message', 'Category updated successfully.');

        // redirect to categories
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check if category exists
        $category = Category::find($id);
        if ($category === null || $category->user_id != Auth::user()->id)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The category does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // delete category
        $category->delete();

        // flash message
        session()->flash('flash_message', 'Category deleted successfully. The transactions with the category are now marked as uncategorized!');

        // redirect to categories
        return redirect()->route('categories.index');
    }
}
