<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UsefulMaterialsCategory,
    UsefulMaterial
};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Admin\Requests\{
    ImageUploadRequest,
    ImageDeleteRequest
};
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function getSlides()
    {
       //
    }

}