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

    public function getSlide($slug)
    {
        $slide = Slide::where('slug', '=', $slug)->first();
        abort_if(!$slide, 422, 'Акцію не знайдено!');

       return response()->json($slide);
    }
}
