<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\PostController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::post('/add/post',  [\App\Http\Controllers\PostController::class, 'addPost'])->middleware(['auth'])->name('add.post');

Route::group(['prefix' => 'post', 'as' => 'post.', 'middleware' => ['auth']], function () {
    Route::get('{id}', [\App\Http\Controllers\PostController::class, 'getPost'])->name('display.post');
    Route::post('{id}', [\App\Http\Controllers\PostController::class, 'addAnswer'])->name('answer.add');
});


require __DIR__.'/auth.php';
