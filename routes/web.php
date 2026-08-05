<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WhyChooseUsController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en')->name('home');

Route::middleware(SetLocale::class.':en')->prefix('en')->name('en.')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{serviceSlug}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/about', AboutController::class)->name('about');
    Route::get('/contact', ContactController::class)->name('contact');
    Route::get('/why-choose-us/{itemSlug}', WhyChooseUsController::class)->name('why.show');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/category/{categorySlug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/{postSlug}', [BlogController::class, 'show'])->name('blog.show');
});

Route::middleware(SetLocale::class.':ru')->prefix('ru')->name('ru.')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/uslugy', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/uslugy/{serviceSlug}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/o-nas', AboutController::class)->name('about');
    Route::get('/kontakty', ContactController::class)->name('contact');
    Route::get('/pochemu-my/{itemSlug}', WhyChooseUsController::class)->name('why.show');
    Route::get('/novosty', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/novosty/kategoriya/{categorySlug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/novosty/{postSlug}', [BlogController::class, 'show'])->name('blog.show');
});

Route::redirect('/services', '/en/services');
Route::redirect('/about', '/en/about');
Route::redirect('/about-us', '/en/about');
Route::redirect('/contact', '/en/contact');
Route::redirect('/blog', '/en/blog');
