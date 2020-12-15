<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Modules\Admin\Entities\Rabc\Admin;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $primarykey = Admin::getInstance()->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' . $primarykey;

        return [
            'admin_name' => [
                'required',
                'max:256',
                'unique:admins,admin_name' . $validate_id
            ],
            'admin_email' => [
                'required',
                'max:256',
                'email',
            ],
            'password' => [
                'confirmed',
            ],
            'password_confirmation' => [

            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'admin_name.required' => '请输入管理员账户！',
            'password.confirmed' => '密码确认不匹配！',
        ];
    }
}
