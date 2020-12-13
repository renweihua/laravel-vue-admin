<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
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

    /**
     * 模型的数据库连接名
     *
     * @var string
     */
    protected $connection = 'connection-name';


    /**
     * 自定义的软删除
     */
    public $is_delete = 1; //是否开启删除（1.开启删除，就是直接删除；）
    public $delete_field = 'is_delete'; //删除字段
}
