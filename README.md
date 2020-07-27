# 学生活动场地申请及管理系统

本项目为南开大学学生活动场地申请系统（学生端）以及一个对应的管理系统（教师端）。

项目基于 [Yii2](https://www.yiiframework.com/) 框架制作。

## 部署步骤

1. 下载项目文件并安装依赖

    安装 Composer，并在项目根目录执行 `composer install` 安装依赖文件。

2. 初始化项目

    在网站根目录执行 `php init` （Linux）或 `init.bat` （Windows），根据需求选择`Development` 或 `Production`。

3. 配置服务器（以 Apache2 为例）

    4.1. 将前台网站根目录设置为 *frontend/web*，后台网站根目录设置为 *backend/web*，入口文件均为 *index.php*。

    4.2. 启用 Apache2 有关插件。

    ```shell script
    a2enmod rewrite
    a2enmod ssl
    service apache2 restart
    ```

    4.3. 在网站的 Apache2 配置文件中配置 URL 转写。

    ```apacheconfig
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.php
    ```

4. 配置数据库

    5.1. 在数据库中新建一个数据库并导入 *database.sql*。必要时需要开启特定的数据库权限确保触发器成功创建。默认管理员工号 `0000000`，密码 `000000`。默认用户学号 `0000000`，密码 `000000`。

    5.2. 修改 *common/config/main-local.php*，写入数据库配置信息，例如：

    ```php
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
