<?php

namespace App\Modules\Admin\Entities\Log;

use App\Models\MonthModel;
use App\Modules\Admin\Entities\Rabc\Admin;

class AdminLoginLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}
