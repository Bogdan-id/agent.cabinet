<?php
/**
 * Created by PhpStorm.
 * User: vitalijmihalisin
 * Date: 2019-06-23
 * Time: 15:58
 */

namespace App\Service;


class CarImageService
{
    public function getFileName($mark, $model)
    {
        if ($model) {
            $fileName = $this->fileExist("{$mark}_{$model}");

            if ($fileName) return $fileName;
        }

        if ($mark) {
            $fileName = $this->fileExist("{$mark}");

            if ($fileName) return $fileName;
        }

        return $this->fileExist("Default");
    }

    private function fileExist(string $fileName)
    {
        if (file_exists(storage_path('/cars/' . $fileName . '.png')))
            return storage_path('/cars/' . $fileName . '.png');

        if (file_exists(storage_path('/cars/' . $fileName . '.jpg')))
            return storage_path('/cars/' . $fileName . '.jpg');

        return null;
    }
}