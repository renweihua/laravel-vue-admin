<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\BannerRequest;
use App\Modules\Admin\Services\BannerService;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    public function __construct(BannerService $bannerService)
    {
        $this->service = $bannerService;
    }

    public function create(BannerRequest $bannerRequest)
    {
        return $this->createService($bannerRequest);
    }

    public function update(BannerRequest $bannerRequest)
    {
        return $this->updateService($bannerRequest);
    }
}
