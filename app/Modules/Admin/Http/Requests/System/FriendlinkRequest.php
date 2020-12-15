<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Banner;
use App\Modules\Admin\Entities\System\Friendlink;
use App\Modules\Admin\Http\Requests\BaseRequest;

class FriendlinkRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $primarykey = Friendlink::getInstance()->getKeyName();
        $validate_id = ',' . request()->input($primarykey, 0) . ',' .  $primarykey;

        return [
            'link_name' => [
                'required',
                'max:256',
                'unique:friendlinks,link_name' . $validate_id
            ],
            'link_url' => [
                'url',
            ],
            'link_cover' => [
                'required',
            ],
            'link_sort' => [
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
            'link_name.required' => '请输入友链名称！',
            'link_cover.required'   => '请上传友链图标！',
        ];
    }
}
