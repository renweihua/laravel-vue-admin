<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Version;
use App\Modules\Admin\Http\Requests\BaseRequest;

class VersionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = Version::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'version_name' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',version_name' . $validate_id
            ],
            'version_number' => [
                'required',
            ],
            'version_content' => [
                'required',
            ],
            'version_sort' => [
                'number',
            ],
            'is_check' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'version_name.required' => '请输入版本名称！',
            'version_number.required'   => '请输入版本号！',
        ];
    }
}
