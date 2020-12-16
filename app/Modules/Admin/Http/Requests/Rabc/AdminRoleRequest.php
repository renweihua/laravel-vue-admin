<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Modules\Admin\Entities\Rabc\AdminRole;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminRoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = AdminRole::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'role_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',role_name' . $validate_id
            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => '请输入角色名称！',
        ];
    }
}
