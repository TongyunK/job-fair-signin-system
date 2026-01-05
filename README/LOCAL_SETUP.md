# 本地开发环境设置指南

本指南详细说明如何使用本地 MySQL 和 Redis 启动后端服务。

## 前置要求

确保已安装以下软件：

- ✅ **PHP 8.1+** 及以下扩展：
  - pdo_mysql
  - redis
  - mbstring
  - xml
  - gd
  - zip
- ✅ **Composer** (PHP 包管理器)
- ✅ **MySQL 8.0** (已启动服务)
- ✅ **Redis 6.0** (已启动服务)

### 检查安装

```bash
# 检查PHP版本
php -v

# 检查PHP扩展
php -m | grep -E "pdo_mysql|redis|mbstring"

# 检查Composer
composer --version

# 检查MySQL
mysql --version
# 或
mysql -u root -p -e "SELECT VERSION();"

# 检查Redis
redis-cli ping
# 应该返回 PONG
```

## 完整设置步骤

### 步骤 1: 启动 MySQL 和 Redis 服务

#### Windows

**MySQL:**
- 如果使用 XAMPP: 在 XAMPP 控制面板启动 MySQL
- 如果使用 Laragon: 自动启动
- 如果单独安装: 在服务管理器中启动 MySQL 服务

**Redis:**
- 使用 [Memurai](https://www.memurai.com/) (Windows Redis 替代品)
- 或使用 Docker: `docker run -d -p 6379:6379 redis:6.0-alpine`

#### Mac

```bash
# 使用 Homebrew 启动
brew services start mysql
brew services start redis

# 或手动启动
mysql.server start
redis-server
```

#### Linux

```bash
# 启动服务
sudo service mysql start
sudo service redis-server start

# 或使用 systemd
sudo systemctl start mysql
sudo systemctl start redis
```

### 步骤 2: 创建数据库

使用 MySQL 命令行或图形化工具创建数据库：

```bash
# 方式1: 使用命令行
mysql -u root -p

# 在MySQL命令行中执行：
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

```sql
-- 方式2: 使用SQL文件
-- 创建文件 create_database.sql
CREATE DATABASE IF NOT EXISTS job_fair_signin_system 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

```bash
# 执行SQL文件
mysql -u root -p < create_database.sql
```

### 步骤 3: 配置后端环境

```bash
# 进入后端目录
cd backend

# 安装PHP依赖
composer install
```

### 步骤 4: 配置环境变量

```bash
# 复制环境变量模板
cp .env.example .env
```

编辑 `backend/.env` 文件，修改以下配置：

```env
APP_NAME="Job Fair Sign In System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# ============================================
# 数据库配置 - 本地MySQL
# ============================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_fair_signin_system
DB_USERNAME=root
DB_PASSWORD=你的MySQL密码

# ============================================
# Redis配置 - 本地Redis
# ============================================
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# ============================================
# 其他配置
# ============================================
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
CACHE_STORE=redis
```

> 💡 **提示**: 
> - 如果 MySQL root 用户没有密码，`DB_PASSWORD` 留空即可
> - 如果 Redis 设置了密码，在 `REDIS_PASSWORD` 中填写

### 步骤 5: 生成应用密钥

```bash
cd backend
php artisan key:generate
```

这会自动在 `.env` 文件中生成 `APP_KEY`。

### 步骤 6: 测试数据库连接

```bash
# 测试MySQL连接
php artisan tinker
```

在 Tinker 中执行：
```php
DB::connection()->getPdo();
// 如果成功，会显示 PDO 对象信息
```

退出 Tinker：
```php
exit
```

```bash
# 测试Redis连接
php artisan tinker
```

在 Tinker 中执行：
```php
Redis::ping();
// 应该返回 "PONG"
```

### 步骤 7: 运行数据库迁移

```bash
# 运行所有迁移，创建数据表
php artisan migrate
```

这会创建以下数据表：
- `sign_ins` - 签到记录表
- `queues` - 排队记录表

**预期输出：**
```
Migration table created successfully.
Migrating: 2024_01_01_000001_create_sign_ins_table
Migrated:  2024_01_01_000001_create_sign_ins_table (XX.XXms)
Migrating: 2024_01_01_000002_create_queues_table
Migrated:  2024_01_01_000002_create_queues_table (XX.XXms)
```

### 步骤 8: 验证数据库表

```bash
# 方式1: 使用MySQL命令行
mysql -u root -p job_fair_signin_system
SHOW TABLES;
EXIT;

# 方式2: 使用Laravel命令
php artisan db:show
```

应该看到以下表：
- `migrations`
- `sign_ins`
- `queues`

### 步骤 9: 启动后端服务

```bash
php artisan serve
```

后端服务将运行在：**http://localhost:8000**

**测试后端是否正常运行：**
```bash
# 在浏览器访问
http://localhost:8000

# 或使用curl
curl http://localhost:8000
```

应该看到 JSON 响应：
```json
{"message":"Job Fair Sign In System API"}
```

### 步骤 10: 测试 API 接口

```bash
# 测试API路由
curl http://localhost:8000/api/queue/current
```

应该返回：
```json
{
  "success": true,
  "data": {
    "waiting": 0,
    "processing": 0,
    "completed": 0,
    "total": 0
  }
}
```

## 常见问题排查

### 问题 1: MySQL 连接失败

**错误信息：**
```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

**解决方法：**
1. 检查 MySQL 服务是否启动
2. 检查 `DB_HOST` 是否为 `127.0.0.1` 或 `localhost`
3. 检查 `DB_PORT` 是否为 `3306`
4. 检查用户名和密码是否正确

```bash
# 测试MySQL连接
mysql -u root -p -h 127.0.0.1
```

### 问题 2: Redis 连接失败

**错误信息：**
```
Connection refused [tcp://127.0.0.1:6379]
```

**解决方法：**
1. 检查 Redis 服务是否启动
2. 检查 `REDIS_HOST` 是否为 `127.0.0.1`
3. 检查 `REDIS_PORT` 是否为 `6379`

```bash
# 测试Redis连接
redis-cli ping
# 应该返回 PONG
```

### 问题 3: 迁移失败

**错误信息：**
```
SQLSTATE[42S02]: Base table or view not found
```

**解决方法：**
1. 确保数据库已创建
2. 检查 `.env` 中的数据库配置
3. 清除迁移缓存后重试：

```bash
php artisan migrate:fresh
```

### 问题 4: PHP 扩展缺失

**错误信息：**
```
Class 'Redis' not found
```

**解决方法：**
安装 Redis 扩展：

```bash
# Mac (使用PECL)
pecl install redis

# Linux (Ubuntu/Debian)
sudo apt install php-redis

# Windows
# 在 php.ini 中启用 redis 扩展
```

### 问题 5: Composer 依赖安装失败

**解决方法：**
```bash
# 清除Composer缓存
composer clear-cache

# 重新安装
composer install --no-cache
```

## 快速启动脚本

创建 `start-local.sh` (Mac/Linux) 或 `start-local.bat` (Windows) 来快速启动：

### Mac/Linux (`start-local.sh`)

```bash
#!/bin/bash

echo "启动本地开发环境..."

# 检查服务
echo "检查MySQL..."
mysql -u root -p -e "SELECT 1" > /dev/null 2>&1 || echo "⚠️  MySQL未启动，请先启动MySQL"

echo "检查Redis..."
redis-cli ping > /dev/null 2>&1 || echo "⚠️  Redis未启动，请先启动Redis"

# 进入后端目录
cd backend

# 检查.env文件
if [ ! -f .env ]; then
    echo "创建.env文件..."
    cp .env.example .env
    echo "⚠️  请编辑 .env 文件配置数据库连接"
    exit 1
fi

# 检查APP_KEY
if ! grep -q "APP_KEY=base64" .env; then
    echo "生成APP_KEY..."
    php artisan key:generate
fi

# 运行迁移
echo "运行数据库迁移..."
php artisan migrate --force

# 启动服务
echo "启动Laravel开发服务器..."
php artisan serve
```

### Windows (`start-local.bat`)

```batch
@echo off
chcp 65001 >nul
echo 启动本地开发环境...

cd backend

if not exist .env (
    echo 创建.env文件...
    copy .env.example .env
    echo 请编辑 .env 文件配置数据库连接
    pause
    exit /b 1
)

php artisan key:generate
php artisan migrate --force
php artisan serve
```

## 验证清单

完成设置后，请确认：

- [ ] MySQL 服务正在运行
- [ ] Redis 服务正在运行
- [ ] 数据库 `job_fair_signin_system` 已创建
- [ ] `.env` 文件已配置正确
- [ ] `APP_KEY` 已生成
- [ ] 数据库迁移已成功运行
- [ ] 后端服务运行在 http://localhost:8000
- [ ] API 接口可以正常访问

## 下一步

后端启动成功后，可以：

1. **启动前端服务**
   ```bash
   cd frontend
   npm install
   npm run dev
   ```

2. **开始开发**
   - 前端: http://localhost:3000
   - 后端API: http://localhost:8000/api

3. **查看API文档**
   - 查看 `README.md` 中的 API 接口文档部分

