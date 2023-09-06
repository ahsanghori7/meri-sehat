<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    public function show(){
        return view('admin.banner.view');
    }
}
