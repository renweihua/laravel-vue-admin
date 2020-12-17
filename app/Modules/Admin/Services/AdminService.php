<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\Rabc\Admin;

class AdminService extends BaseService
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
        $this->with = ['adminInfo'];
    }
}
