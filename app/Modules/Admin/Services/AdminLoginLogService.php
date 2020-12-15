<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Log\AdminLoginLog;

class AdminLoginLogService extends BaseService
{
    public function __construct(AdminLoginLog $adminLoginLog)
    {
        $this->model = $adminLoginLog;
    }
}
