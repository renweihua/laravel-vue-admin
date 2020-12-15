<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\AdminMenu;

class AdminMenuService extends BaseService
{
    public function __construct(AdminMenu $adminMenu)
    {
        $this->model = $adminMenu;
    }
}
