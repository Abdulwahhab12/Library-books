<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingRecordController;
use App\Http\Controllers\PatronController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::apiResource('books', BookController::class);
Route::apiResource('patrons', PatronController::class);
Route::post('borrow/{bookId}/patron/{patronId}', [BorrowingRecordController::class, 'borrow']);
Route::put('return/{bookId}/patron/{patronId}', [BorrowingRecordController::class, 'return']);
