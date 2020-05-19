<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UsefulMaterialsCategory
};
use Illuminate\Support\Str;

class UsefulMaterialsController extends Controller
{
    public function getCategories()
    {
       $usefulMaterialsCategories = UsefulMaterialsCategory::all();

       return response()->json($usefulMaterialsCategories);
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


    public function updateCategory(Request $request, $id)
    {
        $data = $request->post();
        $usefulMaterialsCategory = UsefulMaterialsCategory::find($id);
        $usefulMaterialsCategory->update([
           'name' => $data['name'],
           'slug' => Str::slug($data['name'], '-')
        ]);

        return response()->json($usefulMaterialsCategory);
    }

    public function destroyCategory($id)
    {
        $usefulMaterialsCategory = UsefulMaterialsCategory::find($id);
        $usefulMaterialsCategory->delete();

        return response()->json([
            'status' => 200
        ]);
    }
}
