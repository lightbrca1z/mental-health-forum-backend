# Railway SQLite データベース問題の解決手順

## 問題
Railwayの本番環境でSQLiteデータベースファイルが見つからないエラーが発生しています。

## 解決方法

### 方法1: Railway SSHで直接修正（最も簡単）

1. **Railwayダッシュボード**で「Deployments」タブを開く
2. 最新のデプロイメントをクリック
3. 「Connect」ボタンをクリックしてSSH接続
4. 以下のコマンドを実行：

```bash
# データベースディレクトリを作成
mkdir -p /var/www/database

# 権限を設定
chmod 755 /var/www/database

# SQLiteデータベースファイルを作成
touch /var/www/database/database.sqlite

# 権限を設定
chmod 666 /var/www/database/database.sqlite

# Laravelの所有者に変更
chown www-data:www-data /var/www/database/database.sqlite

# マイグレーションを実行
php artisan migrate --force
```

### 方法2: 環境変数を設定

Railwayダッシュボードの「Variables」タブで以下を追加：

```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
APP_DEBUG=true
```

### 方法3: Dockerfileを修正

新しいデプロイメントで以下のDockerfileを使用：

```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Create database directory
RUN mkdir -p /var/www/database && \
    chown -R www-data:www-data /var/www/database && \
    chmod 755 /var/www/database

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Create database file
RUN touch /var/www/database/database.sqlite && \
    chown www-data:www-data /var/www/database/database.sqlite && \
    chmod 666 /var/www/database/database.sqlite

# Run migrations
RUN php artisan migrate --force

# Start server
EXPOSE 9000
CMD ["php-fpm"]
```

## 推奨解決策

**方法1（SSH接続）が最も簡単で確実です**。これにより、データベースファイルが作成され、マイグレーションが実行されます。 