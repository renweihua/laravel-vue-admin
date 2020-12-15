<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Banner;

class BannerService extends BaseService
{
    public function __construct(Banner $banner)
    {
        $this->model = $banner;
    }
}
