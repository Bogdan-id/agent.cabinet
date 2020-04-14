<?php

namespace App\Http\Controllers;

use App\Http\Clients\AutoRiaClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModelController extends Controller
{
    public function index (Request $request, AutoRiaClient $client) {
        return $client->getModels(
            $request->get('category'),
            $request->get('mark')
        );
    }
}
