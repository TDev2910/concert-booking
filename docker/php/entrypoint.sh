#!/bin/sh

# Tạo các thư mục storage bằng đường dẫn tuyệt đối
echo "Syncing storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Cấp quyền cho toàn bộ thư mục web
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 1. Cài đặt Composer dependencies nếu chưa có
if [ ! -f "/var/www/html/vendor/autoload.php" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# 2. Tạo file .env từ .env.example nếu chưa tồn tại
if [ ! -f "/var/www/html/.env" ]; then
    echo "📄 Creating .env file..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# 3. tạo APP_KEY nếu chưa được thiết lập
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Generating APP_KEY..."
    php artisan key:generate --no-interaction --force
fi

# 4. Chạy Database Migrations và Seeders
echo "🗄️  Running Migrations..."
php artisan migrate --force

echo "✅ Entrypoint finished!"

# Thực thi lệnh chính của container (Supervisor)
exec "$@"
