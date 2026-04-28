<?php

use App\Http\Controllers\API\PDF\PDFController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

Route::prefix('pdf')->group(function(){
    
    Route::get('/{id}', [PDFController::class, 'show'])
        ->middleware('permission:pdf.show');
});
