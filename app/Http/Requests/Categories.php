<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaDash;

class Categories extends FormRequest
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
        $uniqueSlug = Rule::unique('categories');
        if ($this->filled('id')) {
            $uniqueSlug->ignore($this->id)->whereNull('deleted_at');
        }

        return [
            'category_type_id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', new AlphaDash, $uniqueSlug, 'max:255'],
            'parent_id' => ['numeric', 'nullable'],
            'sort' => ['numeric', 'nullable'],
            'is_enable' => ['boolean'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => '名称',
            'slug' => 'スラッグ',
            'parent_id' => '親カテゴリ',
            'admin_icon' => 'アイコン',
        ];
    }
}
