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
        //
    }

    public function createCategory(Request $request)
    {
        $data = $request->post();
        $usefulMaterialsCategory = new UsefulMaterialsCategory;
        $usefulMaterialsCategory->name = $data['name'];
        $usefulMaterialsCategory->slug = Str::slug($data['name'], '-');
        $usefulMaterialsCategory->save();

        return response()->json($usefulMaterialsCategory);
    }


    public function updateCategory($id)
    {
        //
    }

    public function destroyCategory($id)
    {
        //
    }
}
