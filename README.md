# PHP 双语主机产品展示站

一个适合普通 PHP 主机的双语产品展示页面，支持：

- 前台展示 `VPS / Colocation / Dedicated Server`
- 中英文切换
- 商品简介、配置、原价、优惠价、订购链接
- FAQ 和联系方式
- 管理后台登录
- 后台维护商品、FAQ、联系方式和首页 Hero 文案

## 目录结构

- `index.php`: 前台首页
- `admin/login.php`: 后台登录
- `admin/dashboard.php`: 后台管理面板
- `admin/save.php`: 后台表单保存逻辑
- `includes/bootstrap.php`: 公共函数、会话、数据存储
- `storage/site-data.json`: 站点数据文件，首次访问自动生成

## 默认后台账号

- 用户名: `admin`
- 密码: `ChangeMe123!`

请在上线前修改 [includes/config.php](/Users/tlc/Documents/speedtest/includes/config.php)。
如果站点部署在子目录，还可以在同一个文件里设置 `base_url`。

## 本地运行

确保系统已安装 PHP 8.1+，然后在项目根目录执行：

```bash
php -S 127.0.0.1:8000
```

打开：

- 前台: `http://127.0.0.1:8000/index.php`
- 后台: `http://127.0.0.1:8000/admin/login.php`

## 数据说明

- 站点内容保存在 `storage/site-data.json`
- 如果文件不存在，系统会自动写入一份默认示例数据
- 当前实现不依赖数据库，便于快速部署到共享主机或轻量 VPS
