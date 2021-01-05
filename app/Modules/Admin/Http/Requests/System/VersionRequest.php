<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Version;
use App\Modules\Admin\Http\Requests\BaseRequest;

class VersionRequest extends BaseRequest
{
    public function setInstance()
    {
        $this->instance = Version::getInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'version_name' => [
                'required',
                'max:256',
                'unique:' . $this->instance->getTable() . ',version_name' . $this->validate_id
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
