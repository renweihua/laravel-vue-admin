<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Config;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ConfigRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = Config::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'config_title' => [
                'required',
                'max:256',
            ],
            'config_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',config_name' . $validate_id
            ]
        ];
    }

    public function messages()
    {
        return [
            'config_title.required' => '请输入配置标题！',
            'config_name.required'   => '请输入配置标识！',
        ];
    }
}
