<?php

use Illuminate\Support\Facades\Route;
use App\Models\Listing;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// all listings
Route::get('/', function () {
    return view('listings', [
        'heading' => 'Latest listing' ,
        'listings' => Listing::all()
    ]);
});

//one listing
Route::get('/listings/{id}', function($id){
    return view('listing', [
        'listing'=> Listing::find($id)
    ]);
});
