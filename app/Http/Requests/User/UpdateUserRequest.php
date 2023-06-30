<?php

namespace App\Http\Requests\User;

use App\Dtos\User\UpdateUserDto;
use App\Resources\Strings;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|min:2|max:255',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:4|max:255',
            'role' => 'nullable|integer|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => Strings::REQUIRED_FIELD_DOES_NOT_VALIDATE_ERROR,
        ];
    }

    public function toUpdateUserDto(): UpdateUserDto
    {
        return new UpdateUserDto(
            $this->input('name'),
            $this->input('email'),
            $this->input('password'),
            $this->input('role'),
        );
    }
}