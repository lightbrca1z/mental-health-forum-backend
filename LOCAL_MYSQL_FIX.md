# ローカル環境 MySQL 修正手順

## 問題
ローカル環境でもSQLiteの設定が残っているため、データベースエラーが発生しています。

## 解決手順

### 1. バックエンドの.envファイルを修正

`mental-health-forum-backend`フォルダ内の`.env`ファイルを以下の内容に完全に置き換えてください：

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

### 2. MySQLの確認

#### Windows (XAMPP)
1. XAMPP Control PanelでMySQLが起動しているか確認
2. phpMyAdminで`mental_health_forum`データベースが存在するか確認

#### 手動でデータベースを作成する場合：
```sql
CREATE DATABASE mental_health_forum;
```

### 3. キャッシュをクリア

```bash
cd mental-health-forum-backend
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 4. マイグレーションを実行

```bash
php artisan migrate:fresh
```

### 5. フロントエンドの.envファイルを修正

`mental-health-forum-frontend`フォルダ内の`.env`ファイルを以下の内容に修正：

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

### 6. サーバーを再起動

#### バックエンド
```bash
cd mental-health-forum-backend
php artisan serve
```

#### フロントエンド
```bash
cd mental-health-forum-frontend
npm run dev
```

## 確認方法

1. **バックエンド**: http://localhost:8000/api/posts
2. **フロントエンド**: http://localhost:3000

## トラブルシューティング

### MySQL接続エラー
```bash
# MySQLサービスが起動しているか確認
# Windows: XAMPP Control Panel
# macOS: brew services list
# Linux: sudo systemctl status mysql
```

### データベースが存在しない
```sql
CREATE DATABASE mental_health_forum;
```

### マイグレーションエラー
```bash
php artisan migrate:fresh --seed
```

### 環境変数が反映されない
```bash
php artisan config:cache
php artisan config:clear
```

## 重要なポイント

- **SQLiteの設定を完全に削除**し、MySQLの設定のみにする
- **キャッシュをクリア**して設定を反映させる
- **データベースが存在する**ことを確認する
- **両方のサーバーを再起動**する 