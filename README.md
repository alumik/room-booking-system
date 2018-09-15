# 学生活动场地申请及管理系统

本项目为南开大学学生活动场地申请系统（学生端）以及一个对应的管理系统（教师端）。

项目基于 [Yii2](https://www.yiiframework.com/) 框架制作。

## 项目网站

学生活动场地申请系统 [https://rbs.alumik.cn/](https://rbs.alumik.cn/)

学生活动场地管理系统 [https://rbs.alumik.cn/admin/](https://rbs.alumik.cn/admin/)

## 部署步骤

#### 01 下载文件 ####

使用 `git clone` 将网站文件下载至部署目录。

#### 02 安装依赖 ####

在网站根目录执行 `composer install` 安装依赖文件。

#### 03 初始化项目 ####

在网站根目录执行 `php init` （Linux）或 `init.bat` （Windows），选择 "Production" 。

#### 04 配置服务器（以 Apache2 为例） ####

1. 将前台网站根目录设置为 *frontend/web* ，后台网站根目录设置为 *backend/web* ，入口文件均为 *index.php* 。

2. 启用 Apache2 插件。

    ```
    a2enmod rewrite
    a2enmod ssl
    service apache2 restart
    ```

3. 配置 URL 转写。

    ```
    RewriteEngine on
    # If a directory or a file exists, use it directly
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # Otherwise forward it to index.php
    RewriteRule . index.php
    ```

#### 05 配置数据库 ####

1. 数据库中新建一个数据库并导入 *database/database.sql* 。默认管理员账号 `0000000` ，密码 `000000` 。默认用户账号 `0000000` ，密码 `000000` 。

2. 修改 *common/config/main-local.php* ，写入数据库配置信息和邮箱配置信息，例如：

    ```
    <?php
    return [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ],
            'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'viewPath' => '@common/mail',
            ],
        ],
    ];
    ```
