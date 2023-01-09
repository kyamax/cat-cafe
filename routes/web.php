<?php

use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;

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
    return view('index');
});

Route::get("/contact", [ContactController::class, "index"]) -> name("contact");
Route::post("/contact", [ContactController::class, "sendMail"]);
Route::get("/contact/complete", [ContactController::class, "complete"]) -> name("contact.complete");

Route::prefix("/admin")
    ->name("admin.")
    ->group(function(){
        Route::middleware("auth")
            ->group(function(){
                Route::resource("/blogs", AdminBlogController::class)->except("show");

                Route::post('/logout', [AuthController::class, 'logout'])->name("logout");
            });
        Route::middleware("guest")
            ->group(function(){
                Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
                Route::post('/login', [AuthController::class, 'login']);
            });
    });

Route::get("/admin/users/create", [UserController::class, "create"])->name("admin.users.create");
Route::post("/admin/users", [UserController::class, "store"])->name("admin.users.store");
