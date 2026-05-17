<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class FastApiController extends Controller
{
    public function predict()
    {
        $response = Http::get(env('FASTAPI_URL') . '/predict');
        return $response->json();
    }
}
