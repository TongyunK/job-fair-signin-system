# 快速启动指南

## 🚀 完整启动流程（前后端）

### 一、启动后端服务

#### 步骤 1: 配置数据库连接

`.env` 文件已自动配置为：
- 数据库名: `job_fair_signin_system`
- 用户名: `root`
- 密码: 请在 `.env` 文件中设置你的 MySQL 密码

**如果 MySQL root 用户有密码，请编辑 `backend/.env` 文件：**
```env
DB_PASSWORD=你的密码
```

#### 步骤 2: 创建数据库并运行迁移

```powershell
# 1. 创建数据库（在 MySQL 命令行中执行）
mysql -u root -p
CREATE DATABASE job_fair_signin_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# 2. 运行数据库迁移
cd backend
php artisan migrate
```

#### 步骤 3: 启动后端服务

```powershell
# 在 backend 目录下执行
php artisan serve
```

后端 API 将运行在：**http://localhost:8000**

**保持这个终端窗口打开！**

---

### 二、启动前端服务

#### 步骤 1: 安装前端依赖（首次运行）

```powershell
# 在项目根目录下执行
cd frontend
npm install
```

#### 步骤 2: 启动前端开发服务器

```powershell
# 在 frontend 目录下执行
npm run dev
```

前端应用将运行在：**http://localhost:3000**

**保持这个终端窗口也打开！**

---

### 三、访问应用

启动成功后，在浏览器中访问：

- **前端界面**: http://localhost:3000
- **后端API**: http://localhost:8000/api

## ✅ 验证服务

```powershell
# 测试 API
curl http://localhost:8000/api/queue/current
```

## 🔧 配置 Redis（可选）

如果已安装 Redis，确保 Redis 服务正在运行：

```powershell
# Windows 上检查 Redis 服务
services.msc
# 查找 Redis 服务，确保状态为"正在运行"
```

Redis 配置已在 `.env` 中设置为默认值：
- Host: 127.0.0.1
- Port: 6379
- Password: null（如果没有密码）

## 📝 完整配置检查清单

### 后端检查项
- [ ] MySQL 服务已启动
- [ ] 数据库 `job_fair_signin_system` 已创建
- [ ] `backend/.env` 文件中的 `DB_PASSWORD` 已设置
- [ ] Redis 服务已启动（如果使用）
- [ ] 已运行 `php artisan migrate`
- [ ] 后端服务正在运行（http://localhost:8000）

### 前端检查项
- [ ] 已运行 `npm install` 安装依赖
- [ ] 前端服务正在运行（http://localhost:3000）
- [ ] 后端服务已启动（前端需要连接后端API）

## 🔄 快速启动命令（两个终端窗口）

**终端 1 - 后端：**
```powershell
cd backend
php artisan serve
```

**终端 2 - 前端：**
```powershell
cd frontend
npm run dev
```

## 🆘 遇到问题？

### 后端问题
- 查看详细配置指南：`SETUP_GUIDE.md`
- 检查数据库连接：确保 MySQL 服务运行且数据库已创建
- 检查 `.env` 配置：确保 `DB_PASSWORD` 等配置正确

### 前端问题
- 确保后端服务已启动（前端需要连接 http://localhost:8000）
- 检查端口占用：如果 3000 端口被占用，Vite 会自动使用其他端口
- 清除缓存：`npm run dev -- --force`

