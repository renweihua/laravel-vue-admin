<?php

namespace App\Models;

use App\Scopes\DeleteScope;
use App\Traits\Error;
use App\Traits\Instance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use function Symfony\Component\String\s;

class Model extends EloquentModel
{
    use Error;
    use Instance;
    use HasFactory;

    /**
     * 与表关联的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型日期的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';

//    /**
//     * 模型的数据库连接名
//     *
//     * @var string
//     */
//    protected $connection = 'mysql';


    /**
     * 自定义的软删除
     */
    protected $is_delete = 1; //是否开启删除（1.开启删除，就是直接删除；）
    protected $delete_field = 'is_delete'; //删除字段

    public function getIsDelete()
    {
        return $this->is_delete;
    }
    public function getDeleteField()
    {
        return $this->delete_field;
    }


    /**
     * 模型的 "booted" 方法
     *
     * 应用全局作用域
     *
     * @return void
     */
    protected static function booted()
    {
        $self = self::getInstance();
        // 假删除的作用域
        static::addGlobalScope(new DeleteScope($self->getIsDelete(), $self->getDeleteField()));
    }
}
