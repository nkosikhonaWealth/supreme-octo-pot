<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeComponent;
use Filament\Http\Controllers\Auth\PasswordResetLinkController;
use Filament\Http\Controllers\Auth\NewPasswordController;
use App\Livewire\AboutComponent;
use App\Livewire\ServicesComponent;
use App\Livewire\ContactComponent;
use App\Livewire\BusinessProfileComponent;
use App\Livewire\ApplicationGuideComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',HomeComponent::class);
Route::get('/about',AboutComponent::class);
Route::get('/join',ServicesComponent::class);
Route::get('/contact',ContactComponent::class);
Route::get('/profile/information',BusinessProfileComponent::class);
Route::get('/application/guide',ApplicationGuideComponent::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
