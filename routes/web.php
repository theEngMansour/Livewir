<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Livewire\Posts;
use App\Http\Livewire\ShowPosts;
use App\Http\Controllers\ImageController;
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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::resource('/posts', PostController::class);
    Route::get('/posts', function () {
        return view('posts');
    })->name('posts');
    Route::post('/image', [ImageController::class,'store']);
    Route::get('search',[Posts::class,'search'])->name('search.froms');
    Route::get('/{slug}',ShowPosts::class)->name('show.post');
   
});





