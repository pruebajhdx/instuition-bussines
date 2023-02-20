<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@login');
Route::post('logout', 'App\Http\Controllers\UserController@logout');

Route::get('profile-images/{filename}', function ($filename) {
    $path = storage_path('app/public/profile_images/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::get('/ask-form', 'App\Http\Controllers\AskFormController@index');

Route::middleware('auth:api')->group(function () {
    Route::post('/ask-form', 'App\Http\Controllers\AskFormController@store');
    Route::put('/ask-form/{id}', 'App\Http\Controllers\AskFormController@update');
    //Route::delete('/ask-form/{id}', 'Api\AskFormController@destroy');
    //Route::get('/ask-form/latest', 'Api\AskFormController@latest');
    Route::get('/users', function () {
        if (Auth::user()->role == 'admin') {
            $users = App\Models\User::with('response')->get();
            return $users;
        } else {
            return response('Unauthorized', 401);
        }
    });
});
