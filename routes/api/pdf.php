<?php

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('PDF', function () {
    

    $path = storage_path('resources/app/views/pdf.html');
    // $pdf->loadHTML('<h1>Hola PDF</h1>');
    // $pdf = App::make('dompdf.wrapper');

    $path = storage_path('app/views/pdf.html');
    // $html = file_get_contents($path);
    // $pdf = PDF::loadFile($path);
    // $pdf = PDF::loadHTML($html);
    // Storage::put('public/prueba1.pdf', $pdf->output());

    $pdf = PDF::loadView('pdf', [

    ]); // solo funciona si esta en storage


    return $pdf->stream('documento.pdf');
});
