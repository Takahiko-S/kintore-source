<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;

class ScheduleRequest extends FormRequest
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
            'menu_name' => 'sometimes|required|string|max:50',
            'menu_exercises.*.weight' => 'sometimes|required|integer|between:1,1000',
            'menu_exercises.*.reps' => 'sometimes|required|integer|between:1,1000',
            'menu_exercises.*.memo' => 'nullable|string|max:500',
            'body_part' => [
                'nullable',
                'string',

            ],
            'new_body_part' => [
                'nullable',
                'string',
                'max:10',

            ],


        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ((!empty($this->body_part) && !empty($this->new_body_part)) ||
                (empty($this->body_part) && empty($this->new_body_part))
            ) {
                $validator->errors()->add('body_part_combination', '部位は一つだけ選択するか入力してください。');
            }
        });
    }
}
