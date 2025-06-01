<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;   
use App\Http\Controllers\MainController;
use App\Http\Controllers\Index7Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
Route::get('/', [Index7Controller::class, 'index'])->name('home');

// Статические страницы
// Route::view('/about', 'about')->name('about');        
Route::post('/events', [EventController::class, 'store'])->name('events.store');    
Route::view('/calendar', 'calendar')->name('calendar');
Route::view('/order', 'order')->name('order');         
Route::get('/meropriat', function () {
    return view('meropriat');
})->name('meropriat')->methods(['GET', 'HEAD']);


// Маршруты для аутентификации
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::view('/register', 'register')->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/registration/success', [RegisterController::class, 'showRegistrationSuccess'])->name('registration.success');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Защищенные маршруты
Route::middleware(['auth'])->group(function () {
    Route::get('/index7', [Index7Controller::class, 'index'])->name('index7');
    Route::get('/index', [Index7Controller::class, 'index'])->name('index');
    Route::get('/change-password', [App\Http\Controllers\PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::put('/password/update', [App\Http\Controllers\PasswordController::class, 'update'])->name('password.update')->middleware('web');
});

// Маршруты для мероприятий
Route::resource('events', EventController::class)->only(['index', 'show']);

Route::get('/lich', [EventController::class, 'myEvents'])->middleware('auth')->name('lich');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
    
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    
    Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events');
    Route::delete('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/registrations', [App\Http\Controllers\Admin\RegistrationController::class, 'index'])->name('registrations.index');
    Route::post('/registrations/{user}/approve', [App\Http\Controllers\Admin\RegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{user}/reject', [App\Http\Controllers\Admin\RegistrationController::class, 'reject'])->name('registrations.reject');
});

// Маршруты для организаторов
Route::middleware(['auth'])->group(function () {
    Route::put('/event/{event}/status', [EventController::class, 'updateStatus'])->name('event.status.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
});

Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/events/participate', [EventController::class, 'participate'])->name('events.participate');
Route::get('/about', [PageController::class, 'about']);

Route::post('/participate', [EventController::class, 'participate'])->name('participate');
Route::get('/lich', [EventController::class, 'lich'])->name('lich');
Route::delete('/accepted-events/{id}', [EventController::class, 'destroyAcceptedEvent'])
    ->name('accepted-events.destroy')
    ->middleware('auth');

// Event Ordering Route
Route::post('/event/order', [App\Http\Controllers\EventOrderController::class, 'store'])
    ->name('event.order.store')
    ->middleware('auth');
