<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class Admins extends FormRequest
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
        $admin = Admin::find($this->input('id'));
        $uniqueLoginId = Rule::unique('admins');
        if ($admin) {
            $uniqueLoginId->ignore($admin->id)->whereNull('deleted_at');
        }

        if (Auth::user()->role->identifier === \Config::get('admin.identifier.developer')) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'login_id' => ['required', $uniqueLoginId, 'max:255'],
                'role_id' => ['required', 'numeric'],
                'email' => ['email:rfc', 'nullable'],
                'password' => ['alpha_dash', 'min:8', 'max:255', 'nullable'],
                'is_enable' => ['boolean'],
                'sort' => ['numeric', 'nullable'],
                'permissions' => ['array'],
                'permissions.*.permission' => ['boolean'],
            ];
        }

        if (Auth::user()->role->identifier === \Config::get('admin.identifier.administrator')) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'login_id' => ['required', $uniqueLoginId, 'max:255'],
                'role_id' => ['required', 'numeric'],
                'email' => ['email:rfc', 'nullable'],
                'password' => ['alpha_dash', 'min:8', 'max:255', 'nullable'],
                'is_enable' => ['boolean'],
                'sort' => ['numeric', 'nullable'],
                'permissions' => ['array'],
                'permissions.*.permission' => ['boolean'],
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            // 'login_id' => ['required', $uniqueLoginId, 'max:255'],
            'email' => ['email:rfc', 'nullable'],
            'password' => ['alpha_dash', 'min:8', 'max:255', 'nullable'],
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
            'name' => 'ユーザー名',
            'login_id' => 'ログイン ID',
            'role_id' => '権限',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
