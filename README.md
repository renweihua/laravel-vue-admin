# laravel-vue-admin

#### 介绍

[demo](http://laravel-vue-admin.cnpscy.com/admin)

![首页](public/demo/home.png)

![版本历史记录](public/demo/版本历史记录.png)

![西班牙语](public/demo/西班牙语.png)

![请求日志统计](public/demo/请求日志统计.png)

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
* 任务调度：`php artisan schedule:run`

#### 使用说明

1.  xxxx
2.  xxxx
3.  xxxx

#### 参与贡献

1.  Fork 本仓库
2.  新建 Feat_xxx 分支
3.  提交代码
4.  新建 Pull Request

#### 捐助

![支付宝收款码](https://images.gitee.com/uploads/images/2020/1112/091130_811b3a6c_790553.jpeg "alipay-400.jpg")
![微信收款码](https://images.gitee.com/uploads/images/2020/1112/091305_2592a352_790553.jpeg "wechat-400-width(1).jpg")


#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  Gitee 官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解 Gitee 上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是 Gitee 最有价值开源项目，是综合评定出的优秀开源项目
5.  Gitee 官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  Gitee 封面人物是一档用来展示 Gitee 会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
