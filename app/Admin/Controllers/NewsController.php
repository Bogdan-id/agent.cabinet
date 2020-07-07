<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    News,
};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function getNews()
    {
        $news = News::all();

        return response()->json($news);
    }

    public function createNews(Request $request)
    {
        $data = $request->post();
        $news = new News;
        $news->news_title_image = $data['newsTitleImage'];
        $news->title = $data['title'];
        $news->slug = Str::slug($data['title'], '-');
        $news->content = $data['content'];
        $news->description = $data['description'];
        $news->save();

        return response()->json($news);
    }

    public function updateNews(Request $request, $id)
    {
        $data = $request->post();
        $news = News::find($id);
        $news->update([
           'news_title_image' => $data['newsTitleImage'],
           'title' => $data['title'],
           'content' => $data['content'],
           'description' => $data['description'],
           'slug' => Str::slug($data['title'], '-')
        ]);

        return response()->json($news);
    }

    public function destroyNews($id)
    {
        $news = News::find($id);
        $news->delete();

        return response()->json([
            'status' => 200
        ]);
    }

}