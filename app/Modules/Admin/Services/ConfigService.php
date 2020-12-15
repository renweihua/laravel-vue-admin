<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Config;

class ConfigService extends BaseService
{
    public function __construct(Config $config)
    {
        $this->model = $config;
    }
}
