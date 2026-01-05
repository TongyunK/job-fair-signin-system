# 快速开始指南

> 💡 **重要提示**：所有环境配置都通过 `.env` 文件管理，**无需修改代码**即可在不同环境间切换。  
> 详细的环境配置说明请查看 [ENVIRONMENT_CONFIG.md](./ENVIRONMENT_CONFIG.md)

> 🚀 **快速启动**：使用本地MySQL和Redis？查看 [QUICK_LOCAL_START.md](./QUICK_LOCAL_START.md) 获取最简步骤

## 方式一：本地开发（推荐用于日常开发）

### 前置要求
- PHP 8.1+ 和 Composer
- MySQL 8.0
- Redis 6.0
- Node.js 18+

### 步骤

1. **安装后端依赖**
```bash
cd backend
composer install
```

2. **配置环境变量**
```bash
cp .env.example .env
# 编辑 .env，设置本地数据库连接
# DB_HOST=127.0.0.1
# DB_DATABASE=job_fair_signin_system
# DB_USERNAME=root
# DB_PASSWORD=你的MySQL密码
# REDIS_HOST=127.0.0.1
```

> 📝 **提示**：只需要修改 `.env` 文件，部署到生产环境时也只需要修改这个文件即可！

3. **初始化数据库**
```bash
# 创建数据库
mysql -u root -p
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 生成密钥并运行迁移
php artisan key:generate
php artisan migrate
```

4. **启动后端服务**
```bash
php artisan serve
# 后端运行在 http://localhost:8000
```

5. **启动前端服务**
```bash
cd ../frontend
npm install
npm run dev
# 前端运行在 http://localhost:3000
```

6. **启动Redis（如果还没启动）**
```bash
# Mac/Linux
redis-server

# Windows (使用Memurai或Docker)
```

✅ 完成！现在可以开始开发了。

---

## 方式二：混合模式（推荐用于团队协作）

只使用Docker运行MySQL和Redis，PHP和前端在本地运行。

### 步骤

1. **启动数据库和Redis（Docker）**
```bash
# 使用开发专用配置
docker-compose -f docker-compose.dev.yml up -d

# 或使用完整配置，只启动数据库和Redis
docker-compose up -d mysql redis
```

2. **配置后端（本地运行）**
```bash
cd backend
composer install
cp .env.example .env

# 编辑 .env，使用Docker中的数据库
# DB_HOST=127.0.0.1
# DB_DATABASE=job_fair_signin_system
# DB_USERNAME=root
# DB_PASSWORD=root
# REDIS_HOST=127.0.0.1

php artisan key:generate
php artisan migrate
php artisan serve
```

3. **启动前端（本地运行）**
```bash
cd frontend
npm install
npm run dev
```

✅ 完成！

---

## 方式三：完全Docker模式（推荐用于部署）

所有服务都在Docker中运行，适合生产环境部署。

### 步骤

1. **启动所有服务**
```bash
docker-compose up -d
```

2. **初始化后端**
```bash
docker-compose exec php composer install
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
```

3. **构建前端**
```bash
cd frontend
npm install
npm run build
```

4. **访问应用**
- 前端: http://localhost
- 后端API: http://localhost/api

✅ 完成！

---

## 选择建议

| 模式 | 适用场景 | 优点 |
|------|---------|------|
| **本地开发** | 日常开发、功能实现 | 速度快、调试方便、资源占用少 |
| **混合模式** | 团队协作、环境一致性 | 数据库环境一致、开发速度快 |
| **完全Docker** | 生产部署、CI/CD | 环境一致、易于部署 |

**推荐流程：**
1. 开发阶段 → 使用本地开发模式
2. 测试阶段 → 使用混合模式
3. 部署阶段 → 使用完全Docker模式

