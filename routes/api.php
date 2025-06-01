<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventParticipationController;

Route::post('/event-participation', [EventParticipationController::class, 'store']); 