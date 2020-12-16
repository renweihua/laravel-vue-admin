<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Banner;
use App\Modules\Admin\Http\Requests\BaseRequest;

class BannerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $instance = Banner::getInstance();
        $primarykey = $instance->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'banner_title' => [
                'required',
                'max:256',
                'unique:' . $instance->getTable() . ',banner_title' . $validate_id
            ],
            'banner_cover' => [
                'required',
            ]
        ];
    }

    public function messages()
    {
        return [
            'banner_title.required' => '请输入Banner标题！',
            'banner_cover.required'   => '请上传Banner封面！',
        ];
    }
}
