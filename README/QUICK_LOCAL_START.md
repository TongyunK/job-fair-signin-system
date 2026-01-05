# 本地开发快速启动指南

使用本地 MySQL 和 Redis 启动后端的快速步骤。

## 一、准备工作

### 1. 确保服务已启动

**MySQL:**
```bash
# Windows: 在服务管理器中启动MySQL，或使用XAMPP/Laragon
# Mac: brew services start mysql
# Linux: sudo service mysql start

# 测试连接
mysql -u root -p
```

**Redis:**
```bash
# Windows: 使用Memurai或Docker运行Redis
# Mac: brew services start redis
# Linux: sudo service redis-server start

# 测试连接
redis-cli ping
# 应该返回: PONG
```

### 2. 创建数据库

```bash
mysql -u root -p
```

在MySQL命令行中执行：
```sql
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

## 二、配置后端

### 1. 进入后端目录并安装依赖

```bash
cd backend
composer install
```

### 2. 配置环境变量

```bash
# 复制环境变量模板
cp .env.example .env
```

编辑 `backend/.env` 文件，修改以下配置：

```env
# 数据库配置
DB_HOST=127.0.0.1
DB_DATABASE=job_fair_signin_system
DB_USERNAME=root
DB_PASSWORD=你的MySQL密码  # 如果没有密码，留空

# Redis配置
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 3. 生成应用密钥

```bash
php artisan key:generate
```

### 4. 运行数据库迁移

```bash
php artisan migrate
```

**预期输出：**
```
Migration table created successfully.
Migrating: 2024_01_01_000001_create_sign_ins_table
Migrated:  2024_01_01_000001_create_sign_ins_table
Migrating: 2024_01_01_000002_create_queues_table
Migrated:  2024_01_01_000002_create_queues_table
```

### 5. 启动后端服务

```bash
php artisan serve
```

后端将运行在：**http://localhost:8000**

## 三、验证安装

### 测试后端API

```bash
# 在浏览器访问
http://localhost:8000

# 或使用curl
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

## 四、使用启动脚本（可选）

### Windows

```bash
start-local.bat
```

### Mac/Linux

```bash
chmod +x start-local.sh
./start-local.sh
```

## 常见问题

### MySQL连接失败

**检查：**
1. MySQL服务是否启动
2. `.env` 中的 `DB_HOST`、`DB_USERNAME`、`DB_PASSWORD` 是否正确
3. 数据库是否已创建

**测试：**
```bash
mysql -u root -p -h 127.0.0.1
```

### Redis连接失败

**检查：**
1. Redis服务是否启动
2. `.env` 中的 `REDIS_HOST` 是否为 `127.0.0.1`

**测试：**
```bash
redis-cli ping
```

### 迁移失败

**解决：**
```bash
# 如果表已存在，可以重置数据库
php artisan migrate:fresh

# 或删除数据库后重新创建
mysql -u root -p
DROP DATABASE job_fair_signin_system;
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
php artisan migrate
```

## 下一步

后端启动成功后：

1. **启动前端**
   ```bash
   cd ../frontend
   npm install
   npm run dev
   ```

2. **访问应用**
   - 前端: http://localhost:3000
   - 后端API: http://localhost:8000/api

---

📖 **详细说明**：查看 [LOCAL_SETUP.md](./LOCAL_SETUP.md) 了解完整的设置步骤和故障排查

