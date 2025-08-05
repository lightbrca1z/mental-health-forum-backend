# Railway MySQL デプロイ手順

## 1. RailwayでMySQLデータベースを作成

1. **Railwayダッシュボード**で「New」→「Database」→「MySQL」を選択
2. データベース名を設定（例：`mental_health_forum`）
3. データベースが作成されると、以下の情報が表示されます：
   - Host
   - Port
   - Database
   - Username
   - Password

## 2. バックエンドプロジェクトをRailwayに接続

1. **Railwayダッシュボード**で「New」→「GitHub Repo」を選択
2. `mental-health-forum-backend`リポジトリを選択
3. デプロイ設定を以下のように設定：

### Build Command
```
docker build -f Dockerfile.mysql -t mental-health-forum-backend .
```

### Start Command
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

## 3. 環境変数の設定

Railwayダッシュボードの「Variables」タブで以下の環境変数を設定：

### 必須環境変数
```
APP_NAME="Mental Health Forum"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mental-health-forum-backend-production.up.railway.app

# MySQL Database Configuration
DB_CONNECTION=mysql
DB_HOST=[MySQLプラグインから取得したHost]
DB_PORT=[MySQLプラグインから取得したPort]
DB_DATABASE=[MySQLプラグインから取得したDatabase]
DB_USERNAME=[MySQLプラグインから取得したUsername]
DB_PASSWORD=[MySQLプラグインから取得したPassword]

# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache Configuration
CACHE_DRIVER=file

# Filesystem Configuration
FILESYSTEM_DISK=local

# Log Configuration
LOG_CHANNEL=stack
LOG_LEVEL=error

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
CORS_ALLOWED_ORIGINS=https://mental-health-forum-frontend-production.up.railway.app
```

## 4. マイグレーションの実行

デプロイ後、RailwayのSSH接続でマイグレーションを実行：

```bash
# SSH接続後
php artisan migrate --force
```

## 5. フロントエンドのデプロイ

1. **Railwayダッシュボード**で「New」→「GitHub Repo」を選択
2. `mental-health-forum-frontend`リポジトリを選択
3. デプロイ設定を以下のように設定：

### Build Command
```
npm run build
```

### Start Command
```
npm start
```

## 6. フロントエンドの環境変数設定

Railwayダッシュボードの「Variables」タブで以下の環境変数を設定：

```
NEXT_PUBLIC_API_URL=https://mental-health-forum-backend-production.up.railway.app/api
NODE_ENV=production
NEXT_PUBLIC_APP_NAME=Mental Health Forum
NEXT_PUBLIC_APP_DESCRIPTION=A supportive community for mental health discussions
```

## 7. 確認方法

デプロイ後、以下のURLでアプリケーションが正常に動作するか確認：

1. **バックエンドAPI**: https://mental-health-forum-backend-production.up.railway.app/api/posts
2. **フロントエンド**: https://mental-health-forum-frontend-production.up.railway.app

## 8. トラブルシューティング

### デプロイエラー
- Build Commandが正しく設定されているか確認
- 環境変数が正しく設定されているか確認

### データベース接続エラー
- MySQLプラグインの接続情報が正しく設定されているか確認
- マイグレーションが実行されているか確認

### CORSエラー
- フロントエンドのURLがCORS設定に含まれているか確認

## 注意事項

- MySQLプラグインから提供される接続情報は、Railwayダッシュボードの「Variables」タブで自動的に設定されます
- データベース接続情報は機密情報なので、適切に管理してください
- デプロイ後、マイグレーションが正常に実行されているか確認してください 