<?php

namespace App\Http\Requests\UploadedImage;

use App\Dtos\UploadedImage\UploadedImageStoreDto;
use App\Resources\Strings;
use Illuminate\Foundation\Http\FormRequest;

class UploadedImageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'productId' => 'nullable|integer',
            'type' => 'required|integer',
            'uploadedImage' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:102400',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => Strings::REQUIRED_FIELD_DOES_NOT_VALIDATE_ERROR,
            'type.integer' => Strings::UPLOADED_IMAGE_TYPE_ERROR,
            'uploadedImage.file' => Strings::UPLOADED_IMAGE_EXTENSION_ERROR,
        ];
    }

    public function toUploadedImageStoreDto(): UploadedImageStoreDto
    {
        return new UploadedImageStoreDto(
            $this->input('productId'),
            $this->input('type'),
            $this->hasFile('uploadedImage') ? $this->uploadedImage : null,
        );
    }
}
