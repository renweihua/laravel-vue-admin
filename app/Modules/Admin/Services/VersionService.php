<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Entities\System\Version;

class VersionService extends BaseService
{
    public function __construct(Version $version)
    {
        $this->model = $version;
    }

    public function lists(array $params): array
    {
        $params['where_callback'] = function($query) use ($params){
            $request = request();
            // 按照名称进行搜索
            if (!empty($search = $request->input('search', ''))){
                $query->where('version_name', 'LIKE', '%' . trim($search) . '%')
                    ->whereOr('version_number', 'LIKE', '%' . trim($search) . '%');
            }
        };
        return parent::lists($params); // TODO: Change the autogenerated stub
    }
}
