<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// The parameters (:,name) indicate that using default guard and using name as authenticate field (default is email)
Route::middleware('auth.basic:,name')->get('/create_token', function (Request $request) {
    $token = $request->user()->createToken('default_token');

    $user = $request->user();
    $user->token =  $token->plainTextToken;

    return redirect('/');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/summary/{project?}', 'SummaryController@getProjectSummary');