.PHONY: help install up up-dev down restart logs build migrate seed clean dev-setup

help: ## 显示帮助信息
	@echo "可用命令:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

dev-setup: ## 本地开发环境设置（只启动MySQL和Redis）
	docker-compose -f docker-compose.dev.yml up -d
	@echo "MySQL和Redis已启动，现在可以在本地运行PHP和前端了"

install: ## 安装项目依赖
	@echo "安装前端依赖..."
	cd frontend && npm install
	@echo "安装后端依赖..."
	docker-compose exec php composer install

up: ## 启动所有服务
	docker-compose up -d
	@echo "等待服务启动..."
	sleep 5
	@echo "服务已启动!"

down: ## 停止所有服务
	docker-compose down

restart: ## 重启所有服务
	docker-compose restart

logs: ## 查看服务日志
	docker-compose logs -f

build: ## 构建Docker镜像
	docker-compose build

migrate: ## 运行数据库迁移
	docker-compose exec php php artisan migrate

seed: ## 运行数据库填充
	docker-compose exec php php artisan db:seed

clean: ## 清理Docker资源
	docker-compose down -v
	docker system prune -f

frontend-dev: ## 启动前端开发服务器
	cd frontend && npm run dev

frontend-build: ## 构建前端生产版本
	cd frontend && npm run build

backend-shell: ## 进入后端容器
	docker-compose exec php bash

mysql-shell: ## 进入MySQL容器
	docker-compose exec mysql mysql -uroot -proot job_fair_signin_system

redis-cli: ## 进入Redis CLI
	docker-compose exec redis redis-cli

