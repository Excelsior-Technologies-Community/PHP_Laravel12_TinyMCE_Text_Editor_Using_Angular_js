<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Show form
Route::get('/', [PostController::class, 'create'])->name('posts.create');

// Handle form submission
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Show all posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Show single post
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');