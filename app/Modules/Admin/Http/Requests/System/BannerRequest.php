<?php

namespace App\Modules\Admin\Http\Requests\System;

use App\Modules\Admin\Entities\System\Banner;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
