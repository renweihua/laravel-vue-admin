<?php

namespace App\Modules\Admin\Entities\Rabc;

use App\Traits\Instance;
use App\Traits\MysqlTable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
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

    /**
     * 不可批量赋值的属性
     *
     * @var array
     */
    protected $guarded = [];

    public function getAdminByName(string $admin_name)
    {
        return $this->where('admin_name', $admin_name)->first();
    }

    public function setPasswordAttribute($key)
    {
        if (empty($key)) unset($this->attributes['password']);
        else $this->attributes['password'] = hash_encryption($key);
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

    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, AdminWithRole::class, 'admin_id', 'role_id')->withPivot(['admin_id', 'role_id']);
    }

    /**
     * @Function         assignRole
     *
     * @param $roles
     *
     * @return bool
     * @author           : cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:给用户分配角色
     * @englishAnnotation:
     */
    public function assignRole($roles)
    {
        return $this->roles()->save($roles);
    }

    /**
     * @Function         deleteRole
     *
     * @param $roles
     *
     * @return mixed
     * @author           : cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:取消用户分配的角色，取消而不是删除
     * @englishAnnotation:
     */
    public function deleteRole($roles)
    {
        return $this->roles()->detach($roles);
    }

    public function getAdminHeadAttribute($key)
    {
        if (empty($key)) return $key;
        return Storage::url($key);
    }

    public function setAdminHeadAttribute($key)
    {
        if (!empty($key)) {
            $this->attributes['admin_head'] = str_replace(Storage::url('/'), '', $key);
        }
    }
}
