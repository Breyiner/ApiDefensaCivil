<?php

namespace App\Http\Controllers\API\PDF;

use App\Http\Controllers\Controller;
use App\Services\PDF\PDFService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PDFController extends Controller
{
    protected PDFService $service;

    public function __construct(PDFService $service)
    {
        // throw new \Exception('Not implemented');
        $this-> service = $service;
    }

    public function show(string $id):Response
    {
        $response = $this->service->byFamilyId($id);
        return $response->stream('plan-familiar.pdf');
    }
}
