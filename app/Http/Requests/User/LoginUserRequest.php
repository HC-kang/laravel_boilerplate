<?php

namespace App\Http\Requests\User;

use App\Dtos\User\LoginUserDto;
use App\Resources\Strings;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string|min:4|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => Strings::REQUIRED_FIELD_DOES_NOT_VALIDATE_ERROR,
        ];
    }

    public function toLoginUserDto(): LoginUserDto
    {
        return new LoginUserDto(
            $this->input('email'),
            $this->input('password'),
        );
    }
}