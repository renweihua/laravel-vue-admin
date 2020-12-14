<?php

namespace App\Modules\Admin\Entities\Rabc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \App\Modules\Admin\Database\factories\Rabc/RoleFactory::new();
    }
}
