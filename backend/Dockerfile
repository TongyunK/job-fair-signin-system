FROM php:8.1-fpm

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-enable pdo_mysql mbstring exif pcntl bcmath gd zip

# 安装 Redis 扩展
RUN pecl install redis && docker-php-ext-enable redis

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 复制 composer 文件
COPY composer.json composer.lock ./

# 安装 PHP 依赖
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 复制应用文件
COPY . .

# 设置权限
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# 暴露端口
EXPOSE 9000

CMD ["php-fpm"]

