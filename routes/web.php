<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Public Blog Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Redirect::route('posts.index');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/category/{category:slug}', [PostController::class, 'postsByCategory'])->name('categories.posts');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

/*
|--------------------------------------------------------------------------
| User/Profile Routes (Requires Auth, does NOT require Admin role)
|--------------------------------------------------------------------------
| Allows all logged-in users to manage their profile and comment.
*/
Route::middleware(['auth'])->group(function () {
    // 1. Comment Posting (Allowed for any logged-in user)
    Route::post('/posts/{post:slug}/comments', [CommentController::class, 'store'])->name('comments.store');

    // 2. Profile Management (Allowed for any logged-in user)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ADMIN Routes (Requires Auth AND Admin role)
|--------------------------------------------------------------------------
| Access to management panels and CRUD operations.
*/
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard & Posts Management (CRUD)
    Route::get('/dashboard', [PostController::class, 'dashboard'])->middleware('verified')->name('dashboard');
    Route::get('/admin/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/admin/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/admin/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/admin/posts/{post:slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/admin/posts/{post:slug}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comment & Category Management
    Route::get('/admin/comments', [PostController::class, 'commentIndex'])->name('comments.index'); 
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/admin/categories/{category:slug}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
});

/*
|--------------------------------------------------------------------------
| Laravel Breeze Authentication Routes (public)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';