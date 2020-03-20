<?php

use Illuminate\Http\Request;

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
Route::post("/login", "LoginController@login");

Route::group(["middleware" => ["jwt.verify"]], function () {
    Route::group(["middleware" => ["access.control:admin"]], function () {
        Route::patch("/client/status/{id}", "ClientController@toggleClientStatus");
        Route::patch("/client/{id}", "ClientController@editClient");
        Route::get("/client", "ClientController@getAllClients");
        Route::put("/client", "ClientController@addClient");
        Route::delete("/client", "ClientController@removeClient");

        Route::get("/metric", "MetricController@getPeerMetrics");
    });
});
