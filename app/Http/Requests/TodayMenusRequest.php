<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodayMenusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:50',
            'menu_exercises.*.weight' => 'sometimes|required|integer|between:1,1000',
            'menu_exercises.*.reps' => 'sometimes|required|integer|between:1,1000',
            'menu_exercises.*.memo' => 'nullable|string|max:500',

        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'メニュー名を入力してください。',
            'menu_exercises.*.weight.between' => '重量は1から1000の間である必要があります。',
            'menu_exercises.*.reps.between' => '回数は1から1000の間である必要があります。',
            // その他のカスタムエラーメッセージ...
        ];
    }
}
