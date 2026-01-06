# 后端本地开发指南

## 快速启动（3步）

### 1. 配置环境

```bash
# 复制环境变量文件
cp .env.example .env

# 编辑 .env，设置数据库连接
# DB_HOST=127.0.0.1
# DB_DATABASE=job_fair_signin_system
# DB_USERNAME=root
# DB_PASSWORD=你的MySQL密码
```

### 2. 初始化数据库

```bash
# 创建数据库（在MySQL中执行）
mysql -u root -p
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 生成密钥并运行迁移
php artisan key:generate
php artisan migrate
```

### 3. 启动服务

```bash
php artisan serve
```

后端运行在：**http://localhost:8000**

## 完整步骤

详细说明请查看项目根目录的：
- [QUICK_LOCAL_START.md](../QUICK_LOCAL_START.md) - 快速启动指南
- [LOCAL_SETUP.md](../LOCAL_SETUP.md) - 完整设置指南

## 验证

```bash
# 测试API
curl http://localhost:8000/api/queue/current
```

## 常用命令

```bash
# 查看路由
php artisan route:list

# 查看数据库表
php artisan db:show

# 进入Tinker（交互式命令行）
php artisan tinker

# 清除缓存
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

