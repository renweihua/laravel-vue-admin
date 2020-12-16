<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait MysqlTable
{
    use Error;

    /**
     * 复制表
     *
     * @param  string  $new_table
     * @param  string  $old_table
     *
     * @return bool
     */
    public function setCopyTable(string $new_table, string $old_table)
    {
        if ( empty($new_table) ) {
            $this->setError('请先设置要生成的新表名');
            return false;
        }
        return DB::select("CREATE TABLE IF NOT EXISTS `{$new_table}` LIKE `{$old_table}`");
    }
}
