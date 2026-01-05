# 本地开发环境配置指南

## 前置要求

1. **MySQL** - 已安装并运行
2. **Redis** - 已安装并运行
3. **PHP 8.1+** - 已安装
4. **Composer** - 已安装

## 步骤 1: 配置环境变量

### 1.1 检查 .env 文件

```powershell
# 在 backend 目录下检查 .env 文件是否存在
cd backend
dir .env
```

如果 `.env` 文件不存在，需要创建它。如果存在，直接编辑即可。

### 1.2 配置 MySQL 连接

编辑 `backend/.env` 文件，设置以下 MySQL 配置：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_fair_signin_system
DB_USERNAME=root
DB_PASSWORD=你的MySQL密码
```

**注意：**
- 如果 MySQL root 用户没有密码，`DB_PASSWORD` 留空即可
- `DB_DATABASE` 是数据库名称，如果不存在需要先创建

### 1.3 配置 Redis 连接

在同一个 `.env` 文件中，设置 Redis 配置：

```env
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

**注意：**
- 如果 Redis 设置了密码，在 `REDIS_PASSWORD` 中填写
- 如果 Redis 没有密码，设置为 `null` 或留空

### 1.4 配置缓存和会话

确保以下配置已设置（使用 Redis）：

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 步骤 2: 创建数据库

### 2.1 使用 MySQL 命令行

```powershell
# 登录 MySQL（会提示输入密码）
mysql -u root -p

# 在 MySQL 中执行以下命令
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2.2 或者使用 MySQL Workbench / phpMyAdmin

创建名为 `job_fair_signin_system` 的数据库，字符集选择 `utf8mb4`，排序规则选择 `utf8mb4_unicode_ci`。

## 步骤 3: 验证 MySQL 和 Redis 连接

### 3.1 测试 MySQL 连接

```powershell
cd backend
php artisan tinker
```

在 Tinker 中执行：

```php
DB::connection()->getPdo();
// 如果成功，会显示 PDO 对象信息
exit
```

### 3.2 测试 Redis 连接

```powershell
php artisan tinker
```

在 Tinker 中执行：

```php
Redis::ping();
// 应该返回 "PONG"
exit
```

## 步骤 4: 初始化数据库

### 4.1 生成应用密钥（如果还没有）

```powershell
php artisan key:generate
```

### 4.2 运行数据库迁移

```powershell
php artisan migrate
```

这会创建以下数据表：
- `sign_ins` - 签到记录表
- `queues` - 排队记录表
- `migrations` - 迁移记录表
- `failed_jobs` - 失败任务表（如果使用队列）

## 步骤 5: 启动后端服务

### 5.1 启动 Laravel 开发服务器

```powershell
php artisan serve
```

后端 API 将运行在：**http://localhost:8000**

### 5.2 验证服务运行

打开浏览器访问：
- http://localhost:8000/api/queue/current

或者使用 PowerShell 测试：

```powershell
curl http://localhost:8000/api/queue/current
```

## 常见问题排查

### MySQL 连接失败

1. **检查 MySQL 服务是否运行**
   ```powershell
   # Windows 服务管理
   services.msc
   # 查找 MySQL 服务，确保状态为"正在运行"
   ```

2. **检查用户名和密码**
   - 确认 `DB_USERNAME` 和 `DB_PASSWORD` 正确
   - 尝试使用 MySQL 客户端直接连接测试

3. **检查数据库是否存在**
   ```sql
   SHOW DATABASES;
   ```

### Redis 连接失败

1. **检查 Redis 服务是否运行**
   ```powershell
   # Windows 上，检查 Redis 服务
   services.msc
   # 或使用命令行
   redis-cli ping
   ```

2. **检查端口是否正确**
   - 默认 Redis 端口是 6379
   - 确认防火墙没有阻止该端口

3. **检查 PHP Redis 扩展**
   ```powershell
   php -m | findstr redis
   ```
   如果没有安装，需要安装 `phpredis` 扩展

### 端口被占用

如果 8000 端口被占用，可以指定其他端口：

```powershell
php artisan serve --port=8001
```

## 完整配置示例

以下是完整的 `.env` 文件配置示例（仅数据库和 Redis 相关部分）：

```env
APP_NAME="Job Fair Sign In System"
APP_ENV=local
APP_KEY=base64:你的应用密钥
APP_DEBUG=true
APP_URL=http://localhost:8000

# MySQL 配置
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_fair_signin_system
DB_USERNAME=root
DB_PASSWORD=

# Redis 配置
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# 缓存和会话配置
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 常用命令

```powershell
# 查看所有路由
php artisan route:list

# 查看数据库表结构
php artisan db:show

# 清除配置缓存
php artisan config:clear

# 清除应用缓存
php artisan cache:clear

# 清除路由缓存
php artisan route:clear

# 查看队列状态
php artisan queue:work

# 进入交互式命令行
php artisan tinker
```

## 下一步

配置完成后，你可以：
1. 启动前端应用（如果有）
2. 测试 API 接口
3. 开始开发新功能

