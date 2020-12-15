<?php

namespace App\Modules\Admin\Entities\System;

use App\Models\Model;

class Version extends Model
{
    protected $primaryKey = 'version_id';
    protected $is_delete = 0;
}
