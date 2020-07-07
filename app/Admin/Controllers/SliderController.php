<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Slide,
};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function getSlides()
    {
        $slides = Slide::all();

       return response()->json($slides);
    }

    public function createSlide(Request $request)
    {
        $data = $request->post();
        $slide = new Slide;
        $slide->slide_image = $data['slideImage'];
        $slide->title = $data['title'];
        $slide->slug = Str::slug($data['title'], '-');
        $slide->content = $data['content'];
        $slide->description = $data['description'];
        $slide->save();

        return response()->json($slide);
    }

    public function updateSlide(Request $request, $id)
    {
        $data = $request->post();
        $slide = Slide::find($id);
        $slide->update([
           'slide_image' => $data['slideImage'],
           'title' => $data['title'],
           'content' => $data['content'],
           'description' => $data['description'],
           'slug' => Str::slug($data['title'], '-')
        ]);

        return response()->json($slide);
    }

    public function destroySlide($id)
    {
        $slide = Slide::find($id);
        $slide->delete();

        return response()->json([
            'status' => 200
        ]);
    }

}