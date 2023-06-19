<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\{Post, User, Group};

use App\Http\Controllers\Admin\{DashboardController, PostController, UserController as AdminUserController, GroupController};

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// routers for Admin management
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function(){

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Posts
    Route::prefix('posts')->name('posts.')->middleware('can:posts')->group(function(){
        Route::get('/', [PostController::class, 'index'])->name('index')->can('viewAny', Post::class);
        Route::get('/show/{post}', [PostController::class, 'show'])->name('show')->withTrashed()->can('view', 'post');

        Route::get('/add', [PostController::class, 'add'])->name('add')->can('create', Post::class);
        Route::post('/add', [PostController::class, 'postAdd'])->can('create', Post::class);

        Route::get('/edit/{post}', [PostController::class, 'edit'])->name('edit')->can('update', 'post');
        Route::post('/edit', [PostController::class, 'postEdit'])->name('postEdit');

        Route::delete('/delete/{post}', [PostController::class, 'delete'])->name('delete')->can('delete', 'post');

        Route::get('/trash', [PostController::class, 'trash'])->name('trash')->can('trash', Post::class);
        Route::get('/postsOfTrashedUsers', [PostController::class, 'postsOfTrashedUsers'])->name('postsOfTrashedUsers')->can('postsOfTrashedUsers', Post::class);
        Route::get('/restore/{post}', [PostController::class, 'restore'])->name('restore')->withTrashed()->can('restore', 'post');   
                                                // need with trash since binding model work only on non-deleted posts
        Route::delete('/destroy/{post}', [PostController::class, 'destroy'])->name('destroy')->withTrashed()->can('forceDelete', 'post');


    });

    // Groups
    Route::prefix('groups')->name('groups.')->middleware('can:groups')->group(function(){
        Route::get('/', [GroupController::class, 'index'])->name('index')->can('viewAny', Group::class);

        Route::get('/add', [GroupController::class, 'add'])->name('add')->can('create', Group::class);
        Route::post('/add', [GroupController::class, 'postAdd'])->can('create', Group::class);

        Route::get('/edit/{group}', [GroupController::class, 'edit'])->name('edit')->can('update', 'group');
        Route::post('/edit', [GroupController::class, 'postEdit'])->name('postEdit');

        Route::delete('/delete/{group}', [GroupController::class, 'delete'])->name('delete')->can('delete', 'group');

        Route::get('/permission/{group}', [GroupController::class, 'permission'])->name('permission')->can('permission', 'group');
        Route::post('/permission', [GroupController::class, 'postPermission'])->name('postPermission');
    });

    // Users
    // Middleware for a group must be placed before group() function.
    // user is Gate, so can() function does not work here. I have to use middleware('can:...') to set up Gate::
    Route::prefix('users')->name('users.')->middleware('can:users')->group(function(){
        Route::get('/', [AdminUserController::class, 'index'])->name('index')->can('viewAny', User::class);

        Route::get('/add', [AdminUserController::class, 'add'])->name('add')->can('create', User::class);
        Route::post('/add', [AdminUserController::class, 'postAdd'])->can('create', User::class);

        Route::get('/edit/{user}', [AdminUserController::class, 'edit'])->name('edit')->can('update', 'user');
        Route::post('/edit', [AdminUserController::class, 'postEdit'])->name('postEdit');

        Route::delete('/delete/{user}', [AdminUserController::class, 'delete'])->name('delete')->can('delete', 'user');

        Route::get('/trash', [AdminUserController::class, 'trash'])->name('trash')->can('trash', User::class);
        Route::get('/restore/{user}', [AdminUserController::class, 'restore'])->name('restore')->withTrashed()->can('restore', 'user');    
                                                // need with trash since binding model work only on non-deleted users
        Route::delete('/destroy/{user}', [AdminUserController::class, 'destroy'])->name('destroy')->withTrashed()->can('forceDelete', 'user');
    });
});



