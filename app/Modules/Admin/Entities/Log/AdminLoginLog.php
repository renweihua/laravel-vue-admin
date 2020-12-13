<?php

namespace App\Modules\Admin\Entities\Log;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminLoginLog extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \App\Modules\Admin\Database\factories\Log/AdminLoginLogFactory::new();
    }
}
