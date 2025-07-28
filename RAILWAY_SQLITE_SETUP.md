# Railway SQLite設定手順

## 1. Railwayでの環境変数設定

Railwayのダッシュボードで「Variables」タブを開き、以下の環境変数を設定してください：

### 必須設定
```
APP_NAME="Mental Health Forum"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mental-health-forum-backend-production.up.railway.app

# データベース設定（SQLite）
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite

# セッション設定
SESSION_DRIVER=file
SESSION_LIFETIME=120

# キャッシュ設定
CACHE_DRIVER=file

# ファイルシステム設定
FILESYSTEM_DISK=local

# ログ設定
LOG_CHANNEL=stack
LOG_LEVEL=error

# メール設定
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# ブロードキャスト設定
BROADCAST_DRIVER=log

# キュー設定
QUEUE_CONNECTION=sync
```

### オプション設定
```
MEMCACHED_HOST=127.0.0.1
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## 2. Dockerfile設定

Railwayの「Settings」タブで、以下のいずれかのDockerfileを使用してください：

### オプション1: 推奨設定
```
docker build -f Dockerfile.sqlite-final -t mental-health-forum-backend .
```

### オプション2: 簡単設定
```
docker build -f Dockerfile.sqlite-simple -t mental-health-forum-backend .
```

## 3. データベース永続化（推奨）

SQLiteデータベースを永続化するために、Railwayの外部ストレージを使用：

1. Railwayダッシュボードで「New」→「Storage」を選択
2. ストレージを作成
3. 環境変数に以下を追加：
   ```
   RAILWAY_STORAGE_MOUNT_PATH=/var/www/database
   ```

## 4. デプロイ後の確認

デプロイが成功したら、以下のエンドポイントでAPIが動作することを確認：

- `GET /api/posts` - 投稿一覧の取得
- `POST /api/posts` - 新規投稿の作成

## 5. トラブルシューティング

### 問題1: SQLite3依存関係エラー
解決策: `Dockerfile.sqlite-final`を使用

### 問題2: データベースファイルが見つからない
解決策: 外部ストレージを設定

### 問題3: 権限エラー
解決策: Dockerfile内で適切な権限設定を確認

## 6. ローカル開発環境との違い

- ローカル: `DB_DATABASE=/c:/Users/User/Desktop/.../database.sqlite`
- Railway: `DB_DATABASE=/var/www/database/database.sqlite`

## 7. 注意事項

- SQLiteは軽量ですが、同時アクセスが多い場合はMySQLの使用を検討
- データベースファイルは定期的にバックアップを取ることを推奨
- 本番環境では`APP_DEBUG=false`に設定 