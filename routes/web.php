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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::post('/add/post',  [\App\Http\Controllers\PostController::class, 'add'])->middleware(['auth'])->name('add.post');

Route::group(['prefix' => 'post', 'as' => 'post.', 'middleware' => ['auth']], function () {
    Route::get('{id}', [\App\Http\Controllers\PostController::class, 'answer_page'])->name('answer.page');
    Route::post('{id}', [\App\Http\Controllers\PostController::class, 'add_answer'])->name('answer.add');
});


require __DIR__.'/auth.php';
