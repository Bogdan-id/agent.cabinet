<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Requests\{
    ImageUploadRequest,
    ImageDeleteRequest
};
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function uploadImage(ImageUploadRequest $request)
    {
        $path = $request->file('upload')->store('uploads', 'public');

        return response()->json([
            'url' => url("/storage/{$path}")
        ]);
    }

    public function deleteImage(ImageDeleteRequest $request)
    {
        $data = $request->validated();
        unlink(public_path("storage/uploads/{$data['image']}")); 
 
        return response()->json([
            'status' => 200
        ]);
    }

}
