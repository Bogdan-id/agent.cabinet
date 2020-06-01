<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    UsefulMaterialsCategory,
    UsefulMaterial
};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Admin\Requests\ImageUploadRequest;

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

    public function createMaterial(Request $request)
    {
        $data = $request->post();
       
        $usefulMaterial = new UsefulMaterial;
        $usefulMaterial->useful_materials_category_id = $data['usefulMaterialsCategoryId'];
        $usefulMaterial->title_image = $data['titleImage'];
        $usefulMaterial->title = $data['title'];
        $usefulMaterial->content = $data['content'];
        $usefulMaterial->save();

        return response()->json($usefulMaterial);
    }

    public function updateMaterial(Request $request, $id)
    {
        $data = $request->post();
        $usefulMaterial = UsefulMaterial::find($id);
        $usefulMaterial->update([
            'useful_materials_category_id' => $data['usefulMaterialsCategoryId'],
            'title_image' =>$data['titleImage'],
            'title' => $data['title'],
            'content' =>$data['content'],
         ]);

        return response()->json($usefulMaterial);
    }

    public function destroyMaterial($id)
    {
        $usefulMaterial = UsefulMaterial::find($id);
        $usefulMaterial->delete();

        return response()->json([
            'status' => 200
        ]);
    }

    public function uploadImage(ImageUploadRequest $request)
    {
        $path = $request->file('image')->store('uploads', 'public');

        return response()->json([
            'url' => $path
        ]);
    }
}
