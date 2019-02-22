## 環境構築

↓のページにすべてがあるので、インストールしてきてください。

[https://github.com/yukure/laravel-docker](https://github.com/yukure/laravel-docker)

## Laravel のセットアップ

ページを表示することはできましたが、まだセットアップは終わってません。
とりあえず、タイムゾーンと言語の設定しておきましょう。
これをしておかないと、ログに吐かれる日時や、DB Recordのcreated_at/updated_atなどが9時間前ずれてしまったり、バリデーションメッセージが日本語ではなく英語になってしまいます。

## タイムゾーンと言語の設定

    // config/app.php
    
    - 'timezone' => 'UTC',
    + 'timezone' => 'Asia/Tokyo',
    
    ...
    
    - 'locale' => 'en',
    + 'locale' => 'ja',

これでOKです。

## mysql の設定

まず、mysqlのコンテナに入ります。

    # コンテナに入る
    docker-compose exec db bash
    
    # mysqlにrootユーザでログインする
    $ mysql -u root -p
    
    # パスワード聞かれるので root と入力
    Enter password:
    
    # ログイン完了！！
    mysql>

ログインできたら、DBの作成をします。

    # laravel という名前のDB作成
    mysql> create database laravel;
    
    # 新しいユーザー作成
    mysql> CREATE USER 'laravel'@'%' IDENTIFIED WITH mysql_native_password BY 'laravel';
    
    # 作ったユーザに権限付与
    GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%'; FLUSH PRIVILEGES;
    
    # 一旦、ログアウト
    mysql> exit
    
    # 先程作ったユーザでログイン (passwordはlaravel)
    $ mysql -u laravel -p
    
    # データベース一覧表示
    mysql> show databases;
    
    +--------------------+
    | Database           |
    +--------------------+
    | information_schema |
    | laravel            |
    +--------------------+
    
    # mysqlからログアウト
    mysql> exit
    
    # コンテナから抜ける
    $ exit

これでmysqlにDB作って、それにアクセスできるユーザの作成をしました。

## .envの編集

次は、`.env`の編集をします。
.envとは、環境変数が格納されているファイルです。

laravelの各種設定の値が一覧になっているファイルです。
このファイルの値を変更すると、laravelのアプリケーション自体に変更がおきるので、取扱は慎重に！

    # 接続先のDBと接続する際のユーザの変更
    DB_CONNECTION=mysql
    -DB_HOST=127.0.0.1
    +DB_HOST=db
    DB_PORT=3306
    - DB_DATABASE=homestead
    + DB_DATABASE=laravel
    - DB_USERNAME=homestead
    + DB_USERNAME=laravel
    - DB_PASSWORD=secret
    + DB_PASSWORD=laravel

## 認証機能(ログイン機能)の実装

webサービスなどでよくある、ログイン機能をlaravelではコマンド１発で（正確には２発）実装できます。

    # php のコンテナに入る
    $ docker-compose exec php bash
    
    # 認証機能実装
    php artisan make:auth
    
    # マイグレーション実行
    php artisan migrate
