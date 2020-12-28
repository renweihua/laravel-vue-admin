<?php

namespace App\Modules\Admin\Http\Requests\Rabc;

use App\Modules\Admin\Entities\Rabc\AdminMenu;
use App\Modules\Admin\Http\Requests\BaseRequest;

class AdminMenuRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = AdminMenu::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'menu_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',menu_name' . $validate_id
            ],
        ];
    }

    public function messages()
    {
        return [
            'menu_name.required' => '请输入菜单名称！',
        ];
    }
}
