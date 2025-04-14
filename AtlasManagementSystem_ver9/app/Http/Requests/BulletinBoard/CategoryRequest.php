<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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

            'main_category_name' => 'string|max:100|unique:main_categories,main_category',

            'main_category_id' => 'exists:main_categories,id',
            'sub_category_name' => 'string|max:100|unique:sub_categories,sub_category',

        ];
    }

    public function messages(){
        return [

            // メインカテゴリー名
            'main_category_name.string' => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max' => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique' => 'そのメインカテゴリーはすでに登録されています。',

            // メインカテゴリーID
            'main_category_id.exists' => '選択されたメインカテゴリーは存在しません。',

            // サブカテゴリー名
            'sub_category_name.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max' => 'サブカテゴリー名は100文字以内で入力してください。',
            'sub_category_name.unique' => 'そのサブカテゴリーはすでに登録されています。',

                    ];
                }
}
