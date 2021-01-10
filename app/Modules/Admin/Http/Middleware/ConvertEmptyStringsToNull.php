<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertEmptyStringsToNull
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->getMethod() == 'GET'){
            // 空字符串自动转换为null的中间件：只有在GET请求时，筛选数据还是需要自动转换的；其余提交时，一律不做过滤
            // 原本只是希望在POST、PUT时，新增与更新数据时，移除自动转换效果。
            (new \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull)->handle($request, $next);
        }

        return $next($request);
    }
}
