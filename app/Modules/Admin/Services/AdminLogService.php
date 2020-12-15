<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Log\AdminLog;

class AdminLogService extends BaseService
{
    public function __construct(AdminLog $adminLog)
    {
        $this->model = $adminLog;
    }
}
