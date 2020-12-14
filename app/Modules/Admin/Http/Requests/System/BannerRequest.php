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
        return [
            'banner_title' => [
                'required',
                'max:256',
                function ($attribute, $value, $fail)
                {
                    $where[] = [$attribute, '=', $value];
                    if (request()->route()->getActionMethod() != 'create') {
                        $primarykey = Banner::getInstance()->getKeyName();
                        $where[]      = [$primarykey, '<>', request()->input($primarykey)];
                    }
                    if ($admin = Banner::firstByWhere($where)) {
                        $fail('该Banner标题已存在！');
                        return;
                    }
                },
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
