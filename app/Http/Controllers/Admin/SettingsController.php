<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    //
    public function index(){
        if (Gate::denies('general-settings-view')) {
            abort(403);
        }
        $settings = Settings::orderBy('group')->get();
        $settings = $settings->groupBy('group');
        return view('admin.settings',['settings'=>$settings]);
    }
    public function update(Request $request)
    {
        if (Gate::denies('general-settings-update')) {
            abort(403);
        }
        unset($request['_token']);
		foreach( $request->input() as $k=>$v ){
            $group_key = $k."_group";
			Settings::where('key','like', $k)
	          ->update(['value' => $v, 'group' => $request->$group_key]);
        }
		return back()->with('success');
    }
}
