# Railway デプロイ設定ガイド

## 環境変数の設定

Railwayダッシュボードの「Variables」タブで以下の環境変数を設定してください：

### 必須環境変数
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
```

### オプション環境変数
```
LOG_CHANNEL=stack
LOG_LEVEL=debug
BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync
SESSION_LIFETIME=120
```

## 500エラーの解決方法

### 1. APP_DEBUGをtrueに設定
本番環境でも一時的にデバッグを有効にして、エラーの詳細を確認：

```
APP_DEBUG=true
```

### 2. ログレベルをdebugに設定
詳細なログを確認：

```
LOG_LEVEL=debug
```

### 3. データベースの確認
SQLiteファイルが正しく作成されているか確認：

```bash
# Railwayのログで以下を確認
# - データベースファイルの作成
# - マイグレーションの実行
# - 権限の設定
```

## デプロイ手順

1. **Railwayでプロジェクトを作成**
   - GitHubリポジトリを選択
   - mental-health-forum-backendディレクトリを指定

2. **環境変数を設定**
   - 上記の環境変数をRailwayダッシュボードで設定
   - **重要**: APP_DEBUG=trueでデバッグを有効化

3. **デプロイ実行**
   - Railwayが自動的にNIXPACKSでビルド
   - Laravelの組み込みサーバーで起動

## トラブルシューティング

### よくある問題

1. **APP_KEYエラー**
   - 環境変数でAPP_KEYを設定するか、自動生成に任せる

2. **データベースエラー**
   - SQLiteファイルが正しく作成されているか確認
   - マイグレーションが正常に実行されているか確認

3. **ポートエラー**
   - $PORT環境変数がRailwayによって自動設定されることを確認

4. **ヘルスチェックエラー**
   - /healthエンドポイントが正しく応答するか確認

5. **500エラー**
   - APP_DEBUG=trueでエラーの詳細を確認
   - ログを確認して具体的なエラーを特定

## 動作確認

デプロイ後、以下のURLで動作確認：

- ヘルスチェック: `https://mental-health-forum-backend-production.up.railway.app/health`
- APIエンドポイント: `https://mental-health-forum-backend-production.up.railway.app/api/posts`
- エラー詳細: `https://mental-health-forum-backend-production.up.railway.app/api/posts` (APP_DEBUG=trueの場合)

## ログの確認方法

1. **Railwayダッシュボード**
   - Backendプロジェクトの「Logs」タブを確認

2. **Laravelログ**
   - APP_DEBUG=trueの場合、エラーの詳細が表示される

3. **データベースログ**
   - マイグレーションの実行状況を確認 