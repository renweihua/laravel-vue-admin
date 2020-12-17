<?php

namespace App\Modules\Admin\Entities\Rabc;

use App\Traits\Instance;
use App\Traits\MysqlTable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Instance;
    use MysqlTable;

    protected $primaryKey = 'admin_id';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    protected $hidden = ['password'];

    public function getAdminByName(string $admin_name)
    {
        return $this->where('admin_name', $admin_name)->first();
    }

    public function setPasswordAttribute($key)
    {
        if (empty($key)) unset($this->attributes['password']);
    }

    /**
     * 获取会储存到 jwt 声明中的标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role' => 'admin'];
    }

    public function adminInfo()
    {
        return $this->hasOne(AdminInfo::class, $this->primaryKey, $this->primaryKey);
    }
}
