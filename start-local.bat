@echo off
chcp 65001 >nul
echo ==========================================
echo 启动本地开发环境
echo ==========================================

REM 检查是否在项目根目录
if not exist "backend" (
    echo 错误: 请在项目根目录运行此脚本
    pause
    exit /b 1
)

REM 进入后端目录
cd backend

REM 检查.env文件
if not exist .env (
    echo 创建.env文件...
    copy .env.example .env
    echo.
    echo ⚠️  重要: 请编辑 backend\.env 文件，配置数据库连接信息
    echo    主要配置项:
    echo    - DB_PASSWORD: 你的MySQL密码
    echo    - DB_DATABASE: job_fair_signin_system (确保数据库已创建)
    echo.
    pause
)

REM 生成APP_KEY
php artisan key:generate >nul 2>&1

REM 运行迁移
echo.
echo 运行数据库迁移...
php artisan migrate --force

if %errorlevel% equ 0 (
    echo.
    echo ==========================================
    echo ✅ 初始化完成！
    echo ==========================================
    echo.
    echo 启动Laravel开发服务器...
    echo 后端API将运行在: http://localhost:8000
    echo.
    echo 按 Ctrl+C 停止服务器
    echo ==========================================
    echo.
    php artisan serve
) else (
    echo.
    echo ❌ 数据库迁移失败，请检查：
    echo    1. MySQL服务是否启动
    echo    2. .env文件中的数据库配置是否正确
    echo    3. 数据库是否已创建
    pause
    exit /b 1
)

