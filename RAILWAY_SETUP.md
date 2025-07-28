# Railway デプロイ設定ガイド

## 環境変数の設定

Railwayダッシュボードの「Variables」タブで以下の環境変数を設定してください：

### 最小限の環境変数（推奨）
```
APP_ENV=production
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite
```

### 完全な環境変数（オプション）
```
APP_NAME="Mental Health Forum"
APP_ENV=production
APP_DEBUG=true
APP_URL=https://mental-health-forum-backend-production.up.railway.app

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
FILESYSTEM_DISK=local

LOG_CHANNEL=stack
LOG_LEVEL=debug
BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync
SESSION_LIFETIME=120
```

## デプロイ手順

1. **Railwayでプロジェクトを作成**
   - GitHubリポジトリを選択
   - mental-health-forum-backendディレクトリを指定

2. **環境変数を設定**
   - 上記の最小限の環境変数を設定
   - **重要**: APP_DEBUG=trueでデバッグを有効化

3. **デプロイ実行**
   - Railwayが自動的にDockerfileでビルド
   - Laravelの組み込みサーバーで起動

## トラブルシューティング

### よくある問題

1. **APP_KEYエラー**
   - 起動スクリプトで自動生成されるため設定不要

2. **データベースエラー**
   - SQLiteファイルが起動時に自動作成される
   - マイグレーションが自動実行される

3. **ポートエラー**
   - $PORT環境変数がRailwayによって自動設定される

4. **ヘルスチェックエラー**
   - /healthエンドポイントが正しく応答するか確認

5. **500エラー**
   - APP_DEBUG=trueでエラーの詳細を確認
   - ログを確認して具体的なエラーを特定

## 動作確認

デプロイ後、以下のURLで動作確認：

- ヘルスチェック: `https://mental-health-forum-backend-production.up.railway.app/health`
- APIエンドポイント: `https://mental-health-forum-backend-production.up.railway.app/api/posts`
- デバッグ情報: `https://mental-health-forum-backend-production.up.railway.app/debug`

## ログの確認方法

1. **Railwayダッシュボード**
   - Backendプロジェクトの「Logs」タブを確認

2. **Laravelログ**
   - APP_DEBUG=trueの場合、エラーの詳細が表示される

3. **起動ログ**
   - データベースファイルの作成
   - マイグレーションの実行
   - サーバーの起動 