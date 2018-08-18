# 学生活动场地申请及管理系统

本项目为南开大学学生活动场地申请系统（学生端）以及一个对应的管理系统（教师端）。

项目基于 [Yii2](https://www.yiiframework.com/) 框架制作。

## 项目网站

学生活动场地申请系统 [https://rbs.ret.red/](https://rbs.ret.red/)

学生活动场地管理系统 [https://admin.rbs.ret.red/](https://admin.rbs.ret.red/)

## 部署步骤

1. 使用 `git clone` 将网站文件获取至网站部署目录。

2. 进入网站根目录， 运行 `composer install` 安装运行环境。

3. 在根目录下执行 `php init` （Linux）或 `init.bat` （Windows），选择运行配置。

4. 将前台网站根目录设置为 `frontend/web` ，后台网站根目录设置为 `backend/web` ，入口文件为 `index.php` 。

5. 确保php允许 `open_basedir` 。

6. 确保网站根目录被赋予了足够的权限。

7. 根据网络服务器的类型配置 URL 转写。 Apache2 的配置如下：
```angular2html
RewriteEngine on
# If a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
# Otherwise forward it to index.php
RewriteRule . index.php
```

8. 数据库中导入 database/database.sql 。默认管理员账号 `0000000` ，密码 `000000` 。默认用户账号 `0000000` ，密码 `000000` 。
