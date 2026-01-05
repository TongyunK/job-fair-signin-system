@echo off
chcp 65001 >nul
echo ==========================================
echo 招聘会签到取号系统 - 启动脚本
echo ==========================================

REM 检查Docker是否运行
docker info >nul 2>&1
if errorlevel 1 (
    echo 错误: Docker未运行，请先启动Docker
    pause
    exit /b 1
)

REM 检查是否存在.env文件
if not exist "backend\.env" (
    echo 创建后端环境配置文件...
    copy backend\.env.example backend\.env
    echo 请编辑 backend\.env 文件配置数据库等信息
)

REM 启动Docker服务
echo 启动Docker服务...
docker-compose up -d

REM 等待服务启动
echo 等待服务启动...
timeout /t 10 /nobreak >nul

REM 检查服务状态
echo 检查服务状态...
docker-compose ps

REM 初始化后端
echo 初始化后端...
docker-compose exec -T php bash -c "if [ ! -f 'vendor/autoload.php' ]; then composer install; fi"
docker-compose exec -T php php artisan key:generate --force
docker-compose exec -T php php artisan migrate --force

echo.
echo ==========================================
echo 服务启动完成！
echo ==========================================
echo 前端开发服务器: http://localhost:3000
echo 后端API: http://localhost/api
echo MySQL: localhost:3306
echo Redis: localhost:6379
echo.
echo 查看日志: docker-compose logs -f
echo 停止服务: docker-compose down
echo ==========================================
pause

