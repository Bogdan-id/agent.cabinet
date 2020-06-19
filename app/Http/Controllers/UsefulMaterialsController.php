<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UsefulMaterialsCategory
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

    public function getMaterialsByCategory($categoryId)
    {
        $category = UsefulMaterialsCategory::find($categoryId);
        abort_if(!$category, 422, 'Категорія не знайдена!');
        $materials = $category->materials;

        return response()->json($materials);
    }
}
