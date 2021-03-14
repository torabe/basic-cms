<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaDash;

class CategoryTypes extends FormRequest
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
        $uniqueSlug = Rule::unique('category_types');
        if ($this->filled('id')) {
            $uniqueSlug->ignore($this->id)->whereNull('deleted_at');
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', new AlphaDash, $uniqueSlug, 'max:255'],
            'select' => ['required', 'string'],
            'is_multiple' => ['required', 'boolean'],
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
            'select' => '選択形式',
            'is_multiple' => '複数選択許可',
        ];
    }
}
