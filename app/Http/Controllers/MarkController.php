<?php

namespace App\Http\Controllers;

use App\Http\Clients\AutoRiaClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarkController extends Controller
{
    public function index (Request $request, AutoRiaClient $client) {
        return $client->getMarks($request->get('category'));
    }
}
