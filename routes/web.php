<?php

use Illuminate\Support\Facades\{ Route};
use App\Http\Common\{ResponseHelper};
use App\Http\Controllers\Api\{AppointmentController};
use App\Http\Controllers\Admin\User\{AuthController};

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login');

Auth::routes(['login' => false]);

// require __DIR__.'/auth.php';

require __DIR__.'/widgets.php';
require __DIR__.'/admin.php';

Route::get('/', function () {
    return view('coming_soon');
    return redirect('login');
})->name('coming_soon');


Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/dashboard', function () {
    return redirect()->route('admin.home');
})->middleware(['auth'])->name('dashboard');

Route::get('/run-cron', [App\Http\Controllers\Api\ApiGeneralController::class, 'runCron']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/newsletter', [App\Http\Controllers\NewsletterController::class, 'newsletter'])->name('newsletter');

Route::get('/user-payment/{id}/{iframe_id}', [App\Http\Controllers\Api\PaymentController::class, 'redirect'])->name('user-payment');
Route::get('/user-payment-response', [App\Http\Controllers\Api\PaymentController::class, 'paymentConfirmation'])->name('payment-confirmation');
Route::get('/user-payment-response-static', [App\Http\Controllers\Api\PaymentController::class, 'paymentConfirmationStatic'])->name('payment-confirmation-static');
Route::get('/redirectToPage', [App\Http\Controllers\Api\PaymentController::class, 'redirectToPage'])->name('redirectToPage');
Route::post('/user-payment-response-processed', [App\Http\Controllers\Api\PaymentController::class, 'paymentConfirmationProcessed'])->name('payment-confirmation-processed');


Route::get('/download-prescription/{appointment}', [AppointmentController::class, 'downloadPrescription'])->missing(function(){
    return ResponseHelper::returnJsonResponse(404, 'Invalid Appointment ID.');
})->name('download.prescription');
Route::get('iframe_template', function (){
    return view('iframe_template');
})->name('iframe_template');

Route::get('/payment/test',function(Request $request){
    return view('payment/testing');
});

Route::get('/payment/test/callback',function(Request $request){
    dd($request);
    return "asdasd";
});


Route::get('/payment/status',function(Request $req){
    dd($req);
});

Route::get('/payment/notification',function(Request $req){
    dd($req);
});

require __DIR__.'/doctor_registration.php';
