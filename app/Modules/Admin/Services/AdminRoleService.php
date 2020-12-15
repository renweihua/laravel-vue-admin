<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\AdminRole;

class AdminRoleService extends BaseService
{
    public function __construct(AdminRole $adminRole)
    {
        $this->model = $adminRole;
    }
}
