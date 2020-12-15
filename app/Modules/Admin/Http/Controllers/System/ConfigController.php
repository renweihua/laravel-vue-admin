<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\ConfigRequest;
use App\Modules\Admin\Services\ConfigService;

class ConfigController extends BaseController
{
    public function __construct(ConfigService $configService)
    {
        $this->service = $configService;
    }

    public function create(ConfigRequest $request)
    {
        return $this->createService($request);
    }

    public function update(ConfigRequest $request)
    {
        return $this->updateService($request);
    }
}
