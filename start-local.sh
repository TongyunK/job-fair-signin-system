#!/bin/bash

echo "=========================================="
echo "启动本地开发环境"
echo "=========================================="

# 检查是否在项目根目录
if [ ! -d "backend" ]; then
    echo "错误: 请在项目根目录运行此脚本"
    exit 1
fi

# 检查MySQL
echo "检查MySQL服务..."
if ! mysql -u root -e "SELECT 1" > /dev/null 2>&1; then
    echo "⚠️  警告: MySQL连接失败，请确保MySQL服务已启动"
    echo "   提示: 检查MySQL服务状态或检查用户名密码"
fi

# 检查Redis
echo "检查Redis服务..."
if ! redis-cli ping > /dev/null 2>&1; then
    echo "⚠️  警告: Redis连接失败，请确保Redis服务已启动"
    echo "   提示: 运行 'redis-server' 或 'brew services start redis'"
fi

# 进入后端目录
cd backend

# 检查.env文件
if [ ! -f .env ]; then
    echo "创建.env文件..."
    cp .env.example .env
    echo ""
    echo "⚠️  重要: 请编辑 backend/.env 文件，配置数据库连接信息"
    echo "   主要配置项:"
    echo "   - DB_PASSWORD: 你的MySQL密码"
    echo "   - DB_DATABASE: job_fair_signin_system (确保数据库已创建)"
    echo ""
    read -p "按回车键继续，或按Ctrl+C退出编辑.env文件..."
fi

# 检查APP_KEY
if ! grep -q "APP_KEY=base64" .env 2>/dev/null; then
    echo "生成APP_KEY..."
    php artisan key:generate
fi

# 检查数据库是否存在
echo "检查数据库..."
DB_NAME=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
if [ -z "$DB_NAME" ]; then
    DB_NAME="job_fair_signin_system"
fi

if ! mysql -u root -e "USE $DB_NAME" > /dev/null 2>&1; then
    echo ""
    echo "⚠️  数据库 '$DB_NAME' 不存在，请先创建数据库："
    echo "   mysql -u root -p"
    echo "   CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    echo ""
    read -p "按回车键继续（如果数据库已创建），或按Ctrl+C退出..."
fi

# 运行迁移
echo ""
echo "运行数据库迁移..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "✅ 初始化完成！"
    echo "=========================================="
    echo ""
    echo "启动Laravel开发服务器..."
    echo "后端API将运行在: http://localhost:8000"
    echo ""
    echo "按 Ctrl+C 停止服务器"
    echo "=========================================="
    echo ""
    php artisan serve
else
    echo ""
    echo "❌ 数据库迁移失败，请检查："
    echo "   1. MySQL服务是否启动"
    echo "   2. .env文件中的数据库配置是否正确"
    echo "   3. 数据库是否已创建"
    exit 1
fi

