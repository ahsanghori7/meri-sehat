<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Gate;
use Yajra\Datatables\Datatables;

class NewsletterController extends Controller
{
    public function newsletter(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ],[
            'email.required' => "Please enter an Email Address.",
            'email.email' => "Please provide a Valid Email Address.",
        ]);
        $newsletter_in_db = Newsletter::where('email', $request->email)->first();
        if($newsletter_in_db){
            return back()->withErrors(['email' => 'This Email already exist. Please provide another Email address.']);
        }
        Newsletter::create([
            'email' => $request->email,
        ]);
        return back()->withErrors(['already_subscribed' => 'Successfully subscribed to Newsletter.']);
    }

    public function view(){
        if (Gate::denies('newsletter-subscribers-view')) {
            abort(403);
        }
        return view('admin.newsletter.view');
    }

    public function fetch(Request $request){
        $query = Newsletter::query();
        if(isset($request->status)) {
            $query = $query->where('status', $request->status);
        }
        return Datatables::of($query)->make(true);
    }
}
