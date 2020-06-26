<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UsefulMaterialsCategory,
    UsefulMaterial
};
use Illuminate\Support\Str;

class UsefulMaterialsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getCategories()
    {
       $usefulMaterialsCategories = UsefulMaterialsCategory::all();

       return response()->json($usefulMaterialsCategories);
    }

    public function getMaterialsByCategory($slug)
    {
        $category = UsefulMaterialsCategory::where('slug', '=', $slug)->first();
        abort_if(!$category, 422, 'Категорія не знайдена!');
        $materials = $category->materials;

        return response()->json($materials);
    }

    public function getMaterialBySlug($categorySlug, $slug)
    {
        $material = UsefulMaterial::where('slug', '=', $slug)->first();
        abort_if(!$material, 422, 'Матеріал не знайдено!');

        return response()->json($material);
    }
}
