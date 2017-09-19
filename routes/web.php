<?php

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

use App\User;

Route::get('/', function () {
    return view('home.index');
})->middleware('auth');

Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::post('/login', 'Auth\LoginController@login')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/verify', 'VerifyController@showVerifyPage')->middleware('auth', 'unverified');
Route::post('/unlink', 'VerifyController@unlink')->middleware('auth', 'verified');

Route::get('/users', function () {
    return User::with('minecraftAccount')->get();
});

// Route::get('/test', function () {
//     $mcUuid = '12c10b5828b54f64a6af98abd443a410';
//     $client = new GuzzleHttp\Client(['base_uri' => 'https://api.mojang.com/']);
//     $response = $client->request('GET', 'user/profiles/' . $mcUuid . '/names');
//     $jsonString = $response->getBody();
//     $json = json_decode($jsonString);
//     // first object in array (current name)
//     $obj = $json[0];
//     // name field {"name":"<username>"}
//     $name = $obj->name;
//     return $name;
// });