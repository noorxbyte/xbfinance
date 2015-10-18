<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Theme;
use App\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display all settings link
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$title = "User Settings";
    	$heading = "User Settings";

    	return view('user.index', compact('title', 'heading'));
    }

    /**
     * Display all settings link
     *
     * @return \Illuminate\Http\Response
     */
    public function changeTheme(Request $request)
    {
        // check if theme exists
        $theme = Theme::find($request->theme);
        if ($theme === null)
        {
            // stuff to pass into view
            $title = "Error";
            $errmsg = "The theme does not exist.";

            return view('errors.error', compact('errmsg', 'title', 'heading'));
        }

        // update user's theme
        $user = User::find(Auth::user()->id);
        $user->theme = $theme->name;
        $user->save();

        // flash message
        session()->flash('flash_message', 'Theme changed successfully.');

        // redirect to transfers list
        return Redirect::to(URL::previous() . "#changeTheme");
    }
}
