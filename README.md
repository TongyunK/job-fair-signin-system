# 招聘会签到取号系统

一个基于 Vue3 + Laravel 10 的招聘会签到取号系统，支持移动端和PC端双端适配。

> 📖 **快速开始**：查看 [QUICK_START.md](./QUICK_START.md) 了解三种启动方式  
> 🚀 **本地开发**：查看 [QUICK_LOCAL_START.md](./QUICK_LOCAL_START.md) 快速启动本地MySQL和Redis  
> 🔧 **开发指南**：查看 [DEVELOPMENT.md](./DEVELOPMENT.md) 了解详细开发流程  
> ⚙️ **环境配置**：查看 [ENVIRONMENT_CONFIG.md](./ENVIRONMENT_CONFIG.md) 了解如何在不同环境间切换  
> 📝 **完整设置**：查看 [LOCAL_SETUP.md](./LOCAL_SETUP.md) 了解详细的本地环境设置步骤

## 技术栈

### 前端
- **核心框架**: Vue 3
- **构建工具**: Vite
- **UI组件库**: 
  - 移动端: Vant UI
  - PC端: Element Plus
- **状态管理**: Pinia
- **路由**: Vue Router
- **样式**: Tailwind CSS
- **国际化**: vue-i18n
- **HTTP客户端**: Axios

### 后端
- **核心框架**: Laravel 10 (PHP 8.1+)
- **数据库**: MySQL 8.0
- **缓存**: Redis 6.0
- **短信服务**: Twilio
- **Excel处理**: Laravel Excel

### 基础设施
- **Web服务器**: Nginx
- **容器化**: Docker & Docker Compose

## 项目结构

```
job-fair-signin-system/
├── frontend/              # 前端项目
│   ├── src/
│   │   ├── api/          # API接口
│   │   ├── components/   # 组件
│   │   ├── i18n/         # 国际化
│   │   ├── router/       # 路由配置
│   │   ├── stores/       # Pinia状态管理
│   │   ├── views/        # 页面视图
│   │   └── main.js       # 入口文件
│   ├── package.json
│   └── vite.config.js
├── backend/               # 后端项目
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   └── Api/  # API控制器
│   │   │   └── Requests/ # 表单验证
│   │   ├── Models/       # 数据模型
│   │   └── Services/     # 业务逻辑服务
│   ├── database/
│   │   └── migrations/   # 数据库迁移
│   ├── routes/
│   │   └── api.php       # API路由
│   └── composer.json
├── nginx/                 # Nginx配置
├── docker-compose.yml     # Docker编排文件
└── README.md
```

## 快速开始

### 前置要求

**本地开发模式：**
- PHP 8.1+ 和 Composer
- MySQL 8.0
- Redis 6.0
- Node.js 18+

**Docker 部署模式：**
- Docker & Docker Compose

### 开发模式：本地运行（推荐用于开发）

适合日常开发调试，前后端都在本地运行，响应速度快，调试方便。

#### 1. 安装本地环境

**安装 PHP 和 Composer：**
- Windows: 使用 [XAMPP](https://www.apachefriends.org/) 或 [Laragon](https://laragon.org/)
- Mac: `brew install php@8.1 composer`
- Linux: `sudo apt install php8.1 php8.1-mysql php8.1-redis composer`

**安装 MySQL 和 Redis：**
- Windows: 使用 XAMPP 或单独安装 MySQL，Redis 使用 [Memurai](https://www.memurai.com/) 或 Docker 只运行 Redis
- Mac: `brew install mysql redis`
- Linux: `sudo apt install mysql-server redis-server`

#### 2. 配置后端

```bash
# 进入后端目录
cd backend

# 安装 PHP 依赖
composer install

# 复制环境变量文件
cp .env.example .env

# 编辑 .env 文件，配置本地数据库连接
# DB_HOST=127.0.0.1
# DB_DATABASE=job_fair_signin_system
# DB_USERNAME=root
# DB_PASSWORD=你的MySQL密码
# REDIS_HOST=127.0.0.1

# 生成应用密钥
php artisan key:generate

# 创建数据库（在MySQL中执行）
# CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 运行数据库迁移
php artisan migrate

# 启动 Laravel 开发服务器
php artisan serve
```

后端API将运行在 http://localhost:8000

#### 3. 配置前端

```bash
# 进入前端目录
cd frontend

# 安装依赖
npm install

# 启动开发服务器
npm run dev
```

前端将运行在 http://localhost:3000，已配置代理将 `/api` 请求转发到 `http://localhost:8000`

#### 4. 启动 Redis（如果还没启动）

```bash
# Windows (使用Memurai或Docker)
# Mac/Linux
redis-server
```

### 生产模式：使用 Docker 启动（推荐用于部署）

1. **克隆项目**
```bash
git clone <repository-url>
cd job-fair-signin-system
```

2. **配置环境变量**

复制后端环境变量文件：
```bash
cp backend/.env.example backend/.env
```

编辑 `backend/.env` 文件，配置数据库和Redis连接信息（Docker环境已自动配置）。

3. **启动服务**

```bash
# 启动所有服务
docker-compose up -d

# 查看服务状态
docker-compose ps

# 查看日志
docker-compose logs -f
```

4. **初始化后端**

```bash
# 进入PHP容器
docker-compose exec php bash

# 安装依赖
composer install

# 生成应用密钥
php artisan key:generate

# 运行数据库迁移
php artisan migrate

# 退出容器
exit
```

5. **构建前端**

```bash
# 进入前端目录
cd frontend

# 安装依赖
npm install

# 开发模式运行
npm run dev

# 或构建生产版本
npm run build
```

6. **访问应用**

- 前端: http://localhost:3000 (开发模式) 或 http://localhost (生产模式)
- 后端API: http://localhost/api
- MySQL: localhost:3306
- Redis: localhost:6379

> 💡 **开发建议**：日常开发推荐使用本地开发模式，不需要Docker。详细说明请查看 [DEVELOPMENT.md](./DEVELOPMENT.md)

## API 接口文档

### 基础URL
```
http://localhost/api
```

### 接口列表

#### 1. 获取排队号
```
POST /api/queue/get-number
```

**请求体:**
```json
{
  "name": "张三",
  "phone": "13800138000",
  "email": "zhangsan@example.com",
  "company": "示例公司",
  "position": "软件工程师"
}
```

**响应:**
```json
{
  "success": true,
  "data": {
    "number": "202401010001",
    "position": 1,
    "status": "waiting",
    "estimatedWaitTime": 0
  }
}
```

#### 2. 查询排队状态
```
GET /api/queue/status/{number}
```

**响应:**
```json
{
  "success": true,
  "data": {
    "number": "202401010001",
    "position": 1,
    "status": "waiting",
    "estimatedWaitTime": 0
  }
}
```

#### 3. 获取当前排队情况
```
GET /api/queue/current
```

**响应:**
```json
{
  "success": true,
  "data": {
    "waiting": 10,
    "processing": 2,
    "completed": 50,
    "total": 62
  }
}
```

## 功能特性

- ✅ 用户签到功能
- ✅ 自动生成排队号
- ✅ 排队状态查询
- ✅ 实时排队位置显示
- ✅ 预计等待时间计算
- ✅ 移动端和PC端自适应
- ✅ 多语言支持（中文/英文）
- ✅ Redis队列管理
- ✅ 数据持久化存储

## 开发模式说明

### 推荐开发流程

1. **开发阶段**：使用本地开发模式
   - 后端：`php artisan serve` (本地运行)
   - 前端：`npm run dev` (本地运行)
   - 数据库：本地MySQL或Docker MySQL
   - Redis：本地Redis或Docker Redis
   - ✅ 开发速度快，调试方便

2. **测试阶段**：使用混合模式
   - 后端和前端本地运行
   - 数据库和Redis使用Docker（环境一致）

3. **部署阶段**：使用Docker统一部署
   - 所有服务都在Docker中运行
   - 环境与生产环境完全一致

详细开发指南请查看 [DEVELOPMENT.md](./DEVELOPMENT.md)

## 开发指南

### 添加新功能

1. **后端开发**
   - 在 `backend/app/Http/Controllers/Api/` 创建控制器
   - 在 `backend/app/Services/` 实现业务逻辑
   - 在 `backend/routes/api.php` 注册路由
   - 创建数据库迁移文件（如需要）

2. **前端开发**
   - 在 `frontend/src/views/` 创建页面组件
   - 在 `frontend/src/api/` 添加API接口
   - 在 `frontend/src/router/index.js` 配置路由
   - 在 `frontend/src/stores/` 添加状态管理（如需要）

### 数据库迁移

```bash
# 创建迁移文件
php artisan make:migration create_table_name

# 运行迁移
php artisan migrate

# 回滚迁移
php artisan migrate:rollback
```

### 代码规范

- 前端使用 ESLint 进行代码检查
- 后端遵循 PSR-12 编码规范
- 使用 Laravel Pint 进行代码格式化

## 环境变量配置

### 后端 (.env)

```env
APP_NAME="Job Fair Sign In System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=job_fair_signin_system
DB_USERNAME=root
DB_PASSWORD=root

REDIS_HOST=redis
REDIS_PORT=6379

TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=your_phone_number
```

## 常见问题

### 1. Docker 容器无法启动

检查端口是否被占用：
```bash
# Windows
netstat -ano | findstr :80
netstat -ano | findstr :3306

# Linux/Mac
lsof -i :80
lsof -i :3306
```

### 2. 数据库连接失败

确保 Docker 容器正在运行：
```bash
docker-compose ps
docker-compose logs mysql
```

### 3. 前端无法连接后端API

检查 `frontend/vite.config.js` 中的代理配置，确保后端服务正在运行。

## 许可证

MIT License

## 贡献

欢迎提交 Issue 和 Pull Request！

