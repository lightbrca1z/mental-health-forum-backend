# SQLite から MySQL への切り替え手順

## 前提条件

1. phpMyAdmin にアクセスできること
2. MySQL データベースを作成する権限があること

## 手順

### 1. phpMyAdmin でのデータベース作成

1. **phpMyAdmin にログイン**
2. **新しいデータベースを作成**:
   - データベース名: `mental_health_forum`
   - 照合順序: `utf8mb4_unicode_ci`
   - 文字セット: `utf8mb4`

### 2. ローカル環境での設定変更

#### 環境変数の設定

`.env` ファイルを以下のように変更：

```env
# データベース設定
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mental_health_forum
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

#### 依存関係の確認

```bash
# MySQL 拡張機能がインストールされているか確認
php -m | grep mysql
```

### 3. マイグレーションの実行

```bash
# 既存のマイグレーションをリセット
php artisan migrate:reset

# マイグレーションを実行
php artisan migrate

# シーダーを実行（必要に応じて）
php artisan db:seed
```

### 4. Railway での設定

#### 環境変数の設定

Railway ダッシュボードで以下の環境変数を設定：

```env
# データベース設定
DB_CONNECTION=mysql
DB_HOST=your_mysql_host
DB_PORT=3306
DB_DATABASE=mental_health_forum
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Dockerfile の変更

Railway は自動的に `Dockerfile.mysql` を使用します。

### 5. 動作確認

#### ローカル環境

```bash
# サーバーを起動
php artisan serve

# ヘルスチェック
curl http://localhost:8000/health

# デバッグエンドポイント
curl http://localhost:8000/debug
```

#### Railway 環境

- `https://your-app-url.railway.app/health`
- `https://your-app-url.railway.app/debug`

## トラブルシューティング

### よくある問題

1. **MySQL 接続エラー**:
   - ホスト名、ポート、ユーザー名、パスワードを確認
   - MySQL サービスが起動しているか確認

2. **権限エラー**:
   - データベースユーザーに適切な権限が付与されているか確認
   - データベースが存在するか確認

3. **文字エンコーディングエラー**:
   - データベースの照合順序が `utf8mb4_unicode_ci` になっているか確認

### ログの確認

```bash
# Laravel ログを確認
tail -f storage/logs/laravel.log

# MySQL ログを確認
sudo tail -f /var/log/mysql/error.log
```

## 注意事項

- 本番環境では適切なパスワードを使用してください
- データベースのバックアップを取ってから切り替えを行ってください
- 既存のデータがある場合は、適切に移行してください

## ファイル変更一覧

- `config/database.php`: デフォルト接続を MySQL に変更
- `railway.json`: MySQL 用 Dockerfile を使用
- `Dockerfile.mysql`: MySQL 対応の Dockerfile
- `railway-mysql-production.env`: MySQL 本番環境用設定 