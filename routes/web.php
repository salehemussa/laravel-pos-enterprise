<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PDFController;

Route::get('/generate-pdf/{id}', [PDFController::class, 'generatePDF']);
