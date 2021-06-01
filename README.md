# laravel-vue-admin

## 作者
[小丑路人](https://www.cnpscy.com)

#### 介绍

[demo](http://laravel-vue-admin.cnpscy.com/admin)

![首页](public/demo/home.png)

![版本历史记录](public/demo/版本历史记录.png)

![西班牙语](public/demo/西班牙语.png)

![请求日志统计](public/demo/请求日志统计.png)

![图片选择器功能](public/demo/图片选择器.png)

![数据库管理](public/demo/数据库管理.png)

![备份管理](public/demo/备份管理.png)

#### 软件架构
软件架构说明

文档地址（无需文档，自己对接）：https://docs.apipost.cn/view/680a60d8e13e4745

#### 安装教程

###### 安装Vue
* 安装 npm 包：`npm install`
* 热更新vue项目：`npm run watch-poll`

    - vue无法执行：可尝试：
        - npm rebuild node-sass
    
###### PHP设置
* 命令行：`composer install`
* 命令行：`cp .env.example .env`
* 命令行，生成 APP_KEY：`php artisan key:generate`
* 命令行，JWT的key：`php artisan jwt:secret`
* 导入根目录sql：laravel-vue-admin.sql
* 定时任务：
    - 自动按月分表：`php artisan command:autotablebuild`
    - 或者使用任务调度：`php artisan schedule:run`

###### 站点配置

- 站点解析目录：`public`
- 访问网址：`你的域名/admin`

#### 使用说明

1.  按月、按年分表的模型，皆不可使用 `with`，可使用 `load` 代替,`static::query` 会重新 实例化当前模型，之前设置的分表名称将被替换。
    
    具体原因看代码：
```php
    /**
     * Begin querying a model with eager loading.
     *
     * @param  array|string  $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function with($relations)
    {
        return static::query()->with(
            is_string($relations) ? func_get_args() : $relations
        );
    }

    ······

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return (new static)->newQuery();
    }
```

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request

#### 捐助

![支付宝收款码](https://images.gitee.com/uploads/images/2020/1112/091130_811b3a6c_790553.jpeg "alipay-400.jpg")
![微信收款码](https://images.gitee.com/uploads/images/2020/1112/091305_2592a352_790553.jpeg "wechat-400-width(1).jpg")