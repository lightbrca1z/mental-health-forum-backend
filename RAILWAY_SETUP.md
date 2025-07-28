# Railway デプロイ設定ガイド

## 環境変数の設定

Railwayダッシュボードの「Variables」タブで以下の環境変数を設定してください：

### 必須環境変数
```
APP_NAME="Mental Health Forum"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-backend-url.railway.app

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
FILESYSTEM_DISK=local
```

### オプション環境変数
```
LOG_CHANNEL=stack
LOG_LEVEL=error
BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync
SESSION_LIFETIME=120
```

## デプロイ手順

1. **Railwayでプロジェクトを作成**
   - GitHubリポジトリを選択
   - mental-health-forum-backendディレクトリを指定

2. **環境変数を設定**
   - 上記の環境変数をRailwayダッシュボードで設定

3. **デプロイ実行**
   - Railwayが自動的にNIXPACKSでビルド
   - Laravelの組み込みサーバーで起動

## トラブルシューティング

### よくある問題

1. **APP_KEYエラー**
   - 環境変数でAPP_KEYを設定するか、自動生成に任せる

2. **データベースエラー**
   - SQLiteファイルが正しく作成されているか確認

3. **ポートエラー**
   - $PORT環境変数がRailwayによって自動設定されることを確認

4. **ヘルスチェックエラー**
   - /healthエンドポイントが正しく応答するか確認

## 動作確認

デプロイ後、以下のURLで動作確認：

- ヘルスチェック: `https://your-backend-url.railway.app/health`
- APIエンドポイント: `https://your-backend-url.railway.app/api/posts` 