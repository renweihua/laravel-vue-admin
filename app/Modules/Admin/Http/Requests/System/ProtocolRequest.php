<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Protocol;
use App\Modules\Admin\Http\Requests\BaseRequest;

class ProtocolRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = Protocol::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'protocol_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',protocol_name' . $validate_id
            ],
            'protocol_type' => [
                'required',
            ],
            'protocol_content' => [
                'required',
            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'protocol_name.required' => '请输入版本名称！',
            'protocol_type.required'   => '请输入版本号！',
        ];
    }
}
