<?php
/**
 *
 *
 * 针对于laravel的方法函数
 *
 *
 */
function getAdminRabcList($admin, $with_func = 'menus')
{
    //管理员的权限获取，并且存储
    $roles = $admin->roles;
    $rabc  = $menu_lists = [];
    foreach ($roles as $key => $role) {
        $menus                = $role->{$with_func}->toArray();
        $rabc[$role->role_id] = array_flip(array_unique(array_column($menus, 'menu_url')));
        $menu_lists           = array_merge($menu_lists, $menus);
    }
    return ['rabc' => $rabc, 'menu' => $menu_lists];
}

function get_request_post()
{
    return request()->isMethod('post');
}

function cnpscy_config(string $config_name = '', string $default = '')
{
    return config('cnpscy.' . $config_name, $default);
}

//快速修改.env文件
function modifyEnv(array $data)
{
    $envPath      = base_path() . DIRECTORY_SEPARATOR . '.env';
    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
    $contentArray->transform(function ($item) use ($data)
    {
        foreach ($data as $key => $value) {
            if (str_contains($item, $key)) {
                return $key . '=' . $value;
            }
        }
        return $item;
    });
    $content = implode($contentArray->toArray(), "\n");
    File::put($envPath, $content);
}

/***************    缓存函数    开始    ***************/

/**
 * [set_cache]
 *
 * @param              [type] $key     [description]
 * @param              [type] $data    [description]
 * @param              [type] $minutes [description]$minutes = 7*24*60*60
 *
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :设置缓存
 * @englishAnnotation  :
 */
function set_cache($key, $data, $minutes = 1 * 60)
{
    return \Cache::put($key, $data, $minutes);
}

/**
 * [get_cache]
 *
 * @param              [type] $key [description]
 *
 * @return             [type]      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :获取缓存的数据
 * @englishAnnotation  :
 */
function get_cache($key)
{
    return \Cache::get($key) ?? '';
}

/**
 * [has_cache]
 *
 * @param              [type]  $key [description]
 *
 * @return             boolean      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :是否存在该key的缓存
 * @englishAnnotation  :
 */
function has_cache($key)
{
    return \Cache::has($key) ? true : false;
}

/**
 * [del_cache]
 *
 * @param              [type] $key [description]
 *
 * @return             [type]      [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :删除缓存
 * @englishAnnotation  :
 */
function del_cache($key)
{
    return \Cache::forget($key) ?? false;
}

/***************    缓存函数    结束    ***************/


/**
 * [logs_data]
 *
 * @param              [type] $data [description]
 *
 * @return             [type]       [description]
 * @author             :cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation  :日志数据的过滤
 * @englishAnnotation  :
 */
function logs_data($data, $ip_agent)
{
    $data['data']         = json_encode(request()->all());
    $data['action']       = request()->route()->getActionName();
    $data['description']  = $data['description'] ?? '';
    $data['add_time']     = $data['add_time'] ?? time();
    $data['ip']           = $ip_agent['ip'] ?? get_ip();
    $data['browser_type'] = $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'];
    return $data;
}

/**
 * 刷新用户权限、角色
 * 多角色管理
 */
if (!function_exists('setAdminRabc')) {
    function setAdminRabc()
    {
        $admin          = auth('admin')->user();
        $roles          = $menus = [];
        $rolesResources = $admin->roles()->orderBy('role_id', 'ASC')->get();
        foreach ($rolesResources as $key => $roleResources) {
            if ($key == 0) $role_id = $roleResources->role_id;
            $roles[$roleResources->role_id] = $roleResources;
            $menus[$roleResources->role_id] = array_filter($roleResources->left_menus()->get()->pluck('api_url')->toArray());
        }

        //默认使用第一个角色
        if (empty($admin->use_role)) \App\Models\BasicAdmin\Admin::where('admin_id', $admin->admin_id)->update(['use_role' => $role_id]);

        // 缓存用户权限
        cache()->forever('admin_rabc_' . $admin->admin_id, [
            'roles' => $roles,
            'menus' => $menus
        ]);
    }
}

/**
 * 获取当前用户-当前角色-权限
 */
if (!function_exists('getAdminRabc')) {
    function getAdminRabc()
    {
        $admin = auth('admin')->user();
        $key   = 'admin_rabc_' . $admin->admin_id;
        if (cache()->has($key)) {
            $admin_rabc = cache($key);
            return [
                'roles' => empty($admin->use_role) ? [] : $admin_rabc['roles'][$admin->use_role],
                'menus' => empty($admin->use_role) ? [] : $admin_rabc['menus'][$admin->use_role],
            ];
        }
        setAdminRabc();
        return cache($key);
    }
}


function getWebUserHome(int $user_id = 0)
{
    return url('user/' . $user_id);
}

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = Str::snake($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return Str::plural($snake_case_name);
}

function get_web_admin_url(string $method, string $controller = '', int $is_compel = 0): string
{
    if (empty($method)) return '';
    $prefix = ltrim(request()->route()->getPrefix(), '/');
    // 如果后台访问地址对不上，关闭下行的注释
    // $prefix = str_replace(head(explode('/', $prefix)), cnpscy_config('web_admin_prefix'), $prefix);
    if (empty($controller) && $is_compel == 0) {
        $url = $prefix . '/' . ltrim($method, '/');
    } else {
        $url = str_replace(last(explode('/', $prefix)), $controller, $prefix) . (empty($controller) ? '' : '/') . ltrim($method, '/');
    }
    return url($url);
}

function get_api_admin_url(string $method, string $controller = '', int $is_compel = 0)
{
    if (empty($method)) return '';
    $prefix = ltrim(request()->route()->getPrefix(), '/');
    $prefix = str_replace(head(explode('/', $prefix)), cnpscy_config('api_admin_prefix'), $prefix);
    if (empty($controller) && $is_compel == 0) {
        $url = 'api/' . $prefix . '/' . ltrim($method, '/');
    } else {
        $url = 'api/' . str_replace(last(explode('/', $prefix)), $controller, $prefix) . (empty($controller) ? '' : '/') . ltrim($method, '/');
    }
    return url($url);
}

function get_model_url(string $method, string $controller = '', int $is_compel = 0)
{
    if (empty($method)) return url('/');
    $prefix = ltrim(request()->route()->getPrefix(), '/');
    if (empty($controller) && $is_compel == 0) {
        $url = $prefix . '/' . ltrim($method, '/');
    } else {
        $url = str_replace(last(explode('/', $prefix)), $controller, $prefix) . (empty($controller) ? '' : '/') . $method;
    }
    return url($url);
}

function getControllerAndFunction()
{
    $action = \Route::current()->getActionName();
    list($class, $method) = explode('@', $action);
    $class = substr(strrchr($class, '\\'), 1);
    return ['controller' => $class, 'method' => $method];
}

function getControllerRoutePrefix()
{
    $controller = getControllerAndFunction()['controller'] ?? '';
    return strtolower(str_replace('Controller', '', $controller));
}
