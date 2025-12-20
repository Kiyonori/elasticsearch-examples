<?php

use App\Http\Controllers\Users;
use Illuminate\Support\Facades\Route;

Route::prefix('rdbms')->name('rdbms.')->group(
    function () {
        Route::prefix('users')->name('users.')->group(
            function () {
                    Route::get('', Users\Rdbms\IndexUsersController::class)
                    ->name('index');
            }
        );
    }
);
