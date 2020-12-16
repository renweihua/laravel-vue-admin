<?php

namespace App\Modules\Admin\Entities\Log;

use App\Models\MonthModel;

class AdminLoginLog extends MonthModel
{
    protected $primaryKey = 'log_id';
    protected $is_delete = 0;
}
