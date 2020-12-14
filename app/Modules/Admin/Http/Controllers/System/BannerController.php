<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Services\BannerService;

class BannerController extends BaseController
{
    public function __construct(BannerService $bannerService)
    {
        $this->service = $bannerService;
    }
}
