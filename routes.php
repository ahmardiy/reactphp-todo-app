<?php

use App\Controllers\MainController;
use QuickRoute\Route;

Route::get('/', [MainController::class, 'index']);