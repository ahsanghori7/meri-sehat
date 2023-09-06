<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\Auth;
use App\Http\Common\Constant;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            $constants = new Constant();
            $user = User::where(['email'=>$request->email])
            ->where('status', true)
            ->whereIn('role_id', [
                $constants->SUPER_ADMIN_ROLE_ID,
                $constants->ADMIN_ROLE_ID,
                $constants->UPLOADERS_ROLE_ID,
                $constants->CONTENT_LEAD_ROLE_ID,
                $constants->BRANDING_LEAD_ROLE_ID,
                $constants->SEO_ROLE_ID,
                $constants->CUST_SERVICE_ROLE_ID,
                $constants->SUBSCRIPTION_ROLE_ID,
                $constants->EDITOR_ROLE_ID,
                $constants->AUTHOR_ROLE_ID,
                $constants->SEO_LEAD_ROLE_ID,
                $constants->CONTENT_TEAM_ROLE_ID,
                $constants->UPLOADERS_WELLNESS_ROLE_ID,
                $constants->UPLOADERS_DISEASE_ROLE_ID,
                $constants->DOCTOR_MANAGEMENT_ROLE_ID
            ])
            ->first();
            if($user) {
                if(Hash::check($request->password, $user->password)) {
                    Auth::login($user);
                    $request->session()->regenerate();
                    return redirect()->intended(RouteServiceProvider::HOME);
                }else{
                    return back()->withErrors(['invalid_credentials' => 'Invalid Credentials']);
                }
            }else{
                return back()->withErrors(['invalid_credentials' => 'Invalid Credentials']);
            }
        }else{
            return view('auth.login');
        }
    }
}
