<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Slide,
};

class SliderController extends Controller
{
    public function getSlides()
    {
        $slides = Slide::all();

       return response()->json($slides);
    }
}
