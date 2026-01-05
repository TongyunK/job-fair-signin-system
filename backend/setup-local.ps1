# 本地开发环境快速配置脚本
# 使用方法: .\setup-local.ps1

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  招聘会签到系统 - 本地环境配置" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# 检查 .env 文件
if (-not (Test-Path .env)) {
    Write-Host "❌ 错误: .env 文件不存在！" -ForegroundColor Red
    Write-Host "请先创建 .env 文件" -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ .env 文件存在" -ForegroundColor Green
Write-Host ""

# 读取当前配置
$envContent = Get-Content .env -Raw

# 配置数据库名称
Write-Host "📝 配置数据库..." -ForegroundColor Yellow
$dbName = Read-Host "请输入数据库名称 (默认: job_fair_signin_system)"
if ([string]::IsNullOrWhiteSpace($dbName)) {
    $dbName = "job_fair_signin_system"
}

# 配置数据库用户名
$dbUser = Read-Host "请输入 MySQL 用户名 (默认: root)"
if ([string]::IsNullOrWhiteSpace($dbUser)) {
    $dbUser = "root"
}

# 配置数据库密码
$dbPass = Read-Host "请输入 MySQL 密码 (如果没有密码直接回车)" -AsSecureString
$dbPassword = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))

# 更新数据库配置
$envContent = $envContent -replace 'DB_DATABASE=.*', "DB_DATABASE=$dbName"
$envContent = $envContent -replace 'DB_USERNAME=.*', "DB_USERNAME=$dbUser"
$envContent = $envContent -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$dbPassword"

# 配置 Redis
Write-Host ""
Write-Host "📝 配置 Redis..." -ForegroundColor Yellow
$redisPass = Read-Host "请输入 Redis 密码 (如果没有密码直接回车)"
if ([string]::IsNullOrWhiteSpace($redisPass)) {
    $redisPass = "null"
}

$envContent = $envContent -replace 'REDIS_PASSWORD=.*', "REDIS_PASSWORD=$redisPass"

# 确保使用 Redis
$envContent = $envContent -replace 'CACHE_STORE=.*', "CACHE_STORE=redis"
$envContent = $envContent -replace 'SESSION_DRIVER=.*', "SESSION_DRIVER=redis"
$envContent = $envContent -replace 'QUEUE_CONNECTION=.*', "QUEUE_CONNECTION=redis"

# 保存配置
$envContent | Set-Content .env -Encoding UTF8

Write-Host ""
Write-Host "✅ 配置已更新！" -ForegroundColor Green
Write-Host ""

# 检查 APP_KEY
if ($envContent -notmatch 'APP_KEY=base64:') {
    Write-Host "🔑 生成应用密钥..." -ForegroundColor Yellow
    php artisan key:generate
    Write-Host ""
}

# 测试 MySQL 连接
Write-Host "🔍 测试 MySQL 连接..." -ForegroundColor Yellow
try {
    $mysqlTest = php artisan tinker --execute="DB::connection()->getPdo(); echo 'MySQL连接成功';" 2>&1
    if ($mysqlTest -match "MySQL连接成功") {
        Write-Host "✅ MySQL 连接成功！" -ForegroundColor Green
    } else {
        Write-Host "⚠️  MySQL 连接测试失败，请检查配置" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  无法测试 MySQL 连接" -ForegroundColor Yellow
}

Write-Host ""

# 测试 Redis 连接
Write-Host "🔍 测试 Redis 连接..." -ForegroundColor Yellow
try {
    $redisTest = php artisan tinker --execute="Redis::ping(); echo 'Redis连接成功';" 2>&1
    if ($redisTest -match "Redis连接成功" -or $redisTest -match "PONG") {
        Write-Host "✅ Redis 连接成功！" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Redis 连接测试失败，请检查 Redis 服务是否运行" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  无法测试 Redis 连接" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  下一步操作：" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. 创建数据库（如果还没有）：" -ForegroundColor Yellow
Write-Host "   mysql -u $dbUser -p" -ForegroundColor White
Write-Host "   CREATE DATABASE $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" -ForegroundColor White
Write-Host ""
Write-Host "2. 运行数据库迁移：" -ForegroundColor Yellow
Write-Host "   php artisan migrate" -ForegroundColor White
Write-Host ""
Write-Host "3. 启动开发服务器：" -ForegroundColor Yellow
Write-Host "   php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan

