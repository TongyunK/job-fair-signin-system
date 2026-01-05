#!/bin/bash

echo "=========================================="
echo "招聘会签到取号系统 - 启动脚本"
echo "=========================================="

# 检查Docker是否运行
if ! docker info > /dev/null 2>&1; then
    echo "错误: Docker未运行，请先启动Docker"
    exit 1
fi

# 检查是否存在.env文件
if [ ! -f "backend/.env" ]; then
    echo "创建后端环境配置文件..."
    cp backend/.env.example backend/.env
    echo "请编辑 backend/.env 文件配置数据库等信息"
fi

# 启动Docker服务
echo "启动Docker服务..."
docker-compose up -d

# 等待服务启动
echo "等待服务启动..."
sleep 10

# 检查服务状态
echo "检查服务状态..."
docker-compose ps

# 进入PHP容器执行初始化
echo "初始化后端..."
docker-compose exec -T php bash << 'EOF'
if [ ! -f "vendor/autoload.php" ]; then
    echo "安装Composer依赖..."
    composer install
fi

if [ -z "$(php artisan key:generate --show)" ]; then
    echo "生成应用密钥..."
    php artisan key:generate
fi

echo "运行数据库迁移..."
php artisan migrate --force

echo "后端初始化完成！"
EOF

echo ""
echo "=========================================="
echo "服务启动完成！"
echo "=========================================="
echo "前端开发服务器: http://localhost:3000"
echo "后端API: http://localhost/api"
echo "MySQL: localhost:3306"
echo "Redis: localhost:6379"
echo ""
echo "查看日志: docker-compose logs -f"
echo "停止服务: docker-compose down"
echo "=========================================="

