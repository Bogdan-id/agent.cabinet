<?php

namespace App\Http\Controllers\Api;

use App\Models\Enums\ObjectTypeEnum;
use App\Models\Service\CarImageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParamController extends Controller
{
    /**
     * @var CarImageService
     */
    private $carImageService;

    public function __construct(CarImageService $carImageService)
    {
        $this->carImageService = $carImageService;
    }

    public function index () {
        $objectTypes = ObjectTypeEnum::customerParams();

        return compact('objectTypes');
    }

    public function image (Request $request) {
        return response()->file(
            $this->carImageService->getFileName(
                $request->get('mark'),
                $request->get('model')
            )
        );
    }

}
