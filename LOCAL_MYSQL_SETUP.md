# ローカル環境 MySQL 設定手順

## 1. MySQLのインストール

### Windows (XAMPP)
1. [XAMPP](https://www.apachefriends.org/)をダウンロードしてインストール
2. XAMPP Control PanelでMySQLを起動
3. phpMyAdminでデータベースを作成：`mental_health_forum`

### macOS
```bash
# Homebrewを使用
brew install mysql
brew services start mysql

# データベースを作成
mysql -u root -p
CREATE DATABASE mental_health_forum;
```

### Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

# データベースを作成
sudo mysql
CREATE DATABASE mental_health_forum;
```

## 2. ローカル環境用の.envファイルを作成

`mental-health-forum-backend`フォルダ内で`.env`ファイルを作成し、以下の内容を追加：

```env
APP_NAME="Mental Health Forum"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# MySQL Database Configuration (Local)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mental_health_forum
DB_USERNAME=root
DB_PASSWORD=

# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache Configuration
CACHE_DRIVER=file

# Filesystem Configuration
FILESYSTEM_DISK=local

# Log Configuration
LOG_CHANNEL=stack
LOG_LEVEL=debug

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Broadcast Configuration
BROADCAST_DRIVER=log

# Queue Configuration
QUEUE_CONNECTION=sync

# CORS Configuration
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

## 3. 依存関係のインストール

```bash
cd mental-health-forum-backend
composer install
```

## 4. アプリケーションキーの生成

```bash
php artisan key:generate
```

## 5. マイグレーションの実行

```bash
php artisan migrate
```

## 6. サーバーの起動

```bash
php artisan serve
```

## 7. フロントエンドの設定

`mental-health-forum-frontend`フォルダ内で`.env`ファイルを作成：

```env
# Local Development Environment Variables

# API Configuration
NEXT_PUBLIC_API_URL=http://localhost:8000/api

# Development Settings
NODE_ENV=development

# Next.js Configuration
NEXT_PUBLIC_APP_NAME=Mental Health Forum
NEXT_PUBLIC_APP_DESCRIPTION=A supportive community for mental health discussions
```

## 8. フロントエンドの起動

```bash
cd mental-health-forum-frontend
npm run dev
```

## 確認方法

1. **バックエンド**: http://localhost:8000/api/posts
2. **フロントエンド**: http://localhost:3000

## トラブルシューティング

### MySQL接続エラー
- MySQLサービスが起動しているか確認
- ポート3306が使用可能か確認
- ユーザー名とパスワードが正しいか確認

### マイグレーションエラー
```bash
php artisan migrate:fresh
```

### CORSエラー
- フロントエンドのURLが正しく設定されているか確認
- バックエンドのCORS設定を確認 