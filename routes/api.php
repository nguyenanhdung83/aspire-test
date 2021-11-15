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

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\V1'], function() {
    //Authentication
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/logout', 'AuthController@logout')->middleware('auth:sanctum')->name('logout');

    Route::group(['middleware' => ['auth:sanctum']], function() {
        //Loans API
        Route::group(['prefix' => 'loans'], function() {
            Route::get('/', 'LoanController@index')->name('loan.index');
            Route::post('/', 'LoanController@store')->name('loan.store');
            Route::get('/{id}', 'LoanController@show')->where('id', '[0-9]+')->name('loan.show');
            Route::get('/{id}/repayments', 'LoanController@repayments')->where('id', '[0-9]+')->name('loan.repayments');
            Route::post('/{id}/repaid', 'LoanController@repaid')->where('id', '[0-9]+')->name('loan.repaid');
        });

        // Repayments API
        Route::get('repayments/{id}', 'RepaymentController@show')->name('repayment.show');
    });
});
