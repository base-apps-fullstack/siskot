<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AccessTokenController as AccessToken;
use App\Http\Controllers\V1\MenuController as Menu;
use App\Http\Controllers\V1\UserController as User;
use App\Http\Controllers\V1\RoleController as Role;

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

Route::prefix('v1')->group(function () {
    Route::post('token', AccessToken::class . '@issueToken');
    Route::get('token/remove', AccessToken::class . '@removeToken');

    Route::get('user', User::class . '@info');
    Route::get('menu', Menu::class . '@index');

    Route::get('role/list', Role::class . '@list');
    Route::get('role/list/available-permissions', Role::class . '@getAll');
    Route::apiResource('role', Role::class);
});
