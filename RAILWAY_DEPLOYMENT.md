# Railway デプロイ手順

## 前提条件

1. Railway アカウントを作成
2. GitHub リポジトリを Railway に接続

## バックエンド（Laravel）のデプロイ

### 1. Railway プロジェクトの作成

1. Railway ダッシュボードで新しいプロジェクトを作成
2. GitHub リポジトリを選択
3. ルートディレクトリを `mental-health-forum-backend` に設定

### 2. 環境変数の設定

Railway ダッシュボードで以下の環境変数を設定：

```
APP_NAME="Mental Health Forum"
APP_ENV=production
APP_KEY=base64:2vPrXWx+nx6eHQnHPEnQRY4mbQ8yQAVt6yxmgmmimU0=
APP_DEBUG=false
APP_URL=https://your-backend-url.railway.app

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
FILESYSTEM_DISK=local
BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync
```

### 3. Dockerfile の選択

プロジェクトには3つの Dockerfile が用意されています：

- `Dockerfile` - Debian ベース（標準）
- `Dockerfile.alpine` - Alpine Linux ベース（軽量）
- `Dockerfile.optimized` - 最適化版（推奨）

Railway は自動的に `Dockerfile.optimized` を使用します。

### 4. アプリケーションキーの生成

ローカルで以下のコマンドを実行してアプリケーションキーを生成：

```bash
cd mental-health-forum-backend
php artisan key:generate --show
```

生成されたキーを `APP_KEY` 環境変数に設定。

### 5. データベースマイグレーションの実行

デプロイ後、Railway ダッシュボードで以下のコマンドを実行：

1. Railway ダッシュボードでプロジェクトを選択
2. 「Deployments」タブをクリック
3. 最新のデプロイメントを選択
4. 「View Logs」をクリック
5. 「Shell」タブをクリック
6. 以下のコマンドを実行：

```bash
# データベースディレクトリを作成
mkdir -p /var/www/database

# マイグレーションを実行
php artisan migrate --force

# データベースの状態を確認
php artisan migrate:status
```

### 6. データベース接続の確認

デバッグエンドポイントでデータベース接続を確認：

```
https://your-backend-url.railway.app/api/debug
```

## フロントエンド（Next.js）のデプロイ

### 1. Railway プロジェクトの作成

1. Railway ダッシュボードで新しいプロジェクトを作成
2. GitHub リポジトリを選択
3. ルートディレクトリを `mental-health-forum-frontend` に設定

### 2. 環境変数の設定

Railway ダッシュボードで以下の環境変数を設定：

```
NEXT_PUBLIC_API_URL=https://your-backend-url.railway.app/api
NODE_ENV=production
NEXT_TELEMETRY_DISABLED=1
```

### 3. デプロイの確認

デプロイが完了したら、以下の点を確認：

1. **アプリケーションの起動**: フロントエンドアプリケーションにアクセス
2. **API 接続**: バックエンドとの通信が正常に動作することを確認
3. **エラーログ**: Railway ダッシュボードでログを確認

## トラブルシューティング

### よくある問題

1. **API 接続エラー**:
   - `NEXT_PUBLIC_API_URL` 環境変数が正しく設定されているか確認
   - バックエンドが正常に動作しているか確認
   - CORS 設定が正しいか確認

2. **データベースエラー**:
   - マイグレーションが実行されているか確認
   - データベース接続設定が正しいか確認
   - `/api/debug` エンドポイントでデータベース状態を確認

3. **ビルドエラー**:
   - Node.js バージョンが 18.0.0 以上であることを確認
   - 依存関係が正しくインストールされているか確認

4. **起動エラー**:
   - ポート設定が正しいか確認
   - 環境変数が正しく設定されているか確認

### ログの確認

Railway ダッシュボードでログを確認して、エラーの詳細を把握できます。

## 注意事項

- フロントエンドはバックエンドに依存しているため、バックエンドを先にデプロイしてください
- 環境変数 `NEXT_PUBLIC_API_URL` は必ず設定してください
- 本番環境では適切なセキュリティ設定を行ってください 