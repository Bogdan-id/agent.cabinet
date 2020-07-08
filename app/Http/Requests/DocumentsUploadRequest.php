<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentsUploadRequest extends JsonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'doc' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,docx,doc,xls,xlsx|max:5120',
        ];
    }

     /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'doc.mimes' => 'Формат файлу не підтримується!',
            'doc.max' => 'Файл повинен бути не більше 5 мб!',
        ];
    }
}
