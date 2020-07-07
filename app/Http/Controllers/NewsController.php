<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    News,
};

class NewsController extends Controller
{
    public function getAllNews()
    {
        $news = News::all();

       return response()->json($news);
    }

    public function getNews($slug)
    {
        $news = News::where('slug', '=', $slug)->first();
        abort_if(!$news, 422, 'Новину не знайдено!');

       return response()->json($news);
    }
}
