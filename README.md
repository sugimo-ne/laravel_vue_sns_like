# Laravel Vue AWS Docker　を使用したtwitterクローンのSPA　
 
 よくある認証・CRUD・フォロー・いいね・コメント・画像投稿が可能なtwitterクローンのLaravelとVueによるSPA
 
## アプリケーションの使用技術
 
フロントエンドにVue.js、バックエンドにLaravelを使用しています。  
環境はlaradockをgit cloneし（git clone https://github.com/Laradock/laradock.git）、  
データベースにpostgreSQLを使用し、  
dockerのコンテナをビルド（docker-compose up -d --build workspace postgres php-fpm nginx）して設定を行なった後  
docker-compose exec workspace npm installなどでフロントエンドの環境を構築しました。  
デプロイはHerokuで行いました。  

_Vue.jsでは、_
- vue-routerを使用して全てフロント側でページの遷移。
- vuexでユーザーログイン情報の保持、エラー処理など。
- axiosでajax通信。
- vuexでユーザーログイン情報エラー処理などを行い、axiosでajax通信。
- FileReaderAPIで画像の読み込み、プレビュー、データ送信。 

_Laravelでは、_
- 使用テンプレートをindex.blade.phpのみでVue側で画面描画を行なっています。
- アプリケーションの実現に必須な様々なAPIを作成、フロントエンドとの連携、認証
- ランダム12桁のID、IDに拡張子をつけたファイル名でAWS S3への画像の保存、その際にDBに保存、トランザクション
- 使用データベースはpostgreSQL
- リレーションなどを活用し、APIに必要データを付与

_VueとLaravelの連携_
- CSRF対策ではjsでクッキーの情報を取り出し、HTTPヘッダーに付与（util.js bootstrap.js）
- Vueで必要な時にLaravelのAPIを叩く、データはなるべく保持
- Vueインスタンス生成前にログイン情報を返すLaravelのAPIをjsで確認し、ログイン時にはVuexのstoreに保持(auth.js)
- （一部）HTTP通信でのエラーの際にレスポンスのメッセージによるエラーハンドリング
- laravelページネーションを活用したクリックで次の15件のデータを新たに取得し、表示させる

などその他細かい処理などがあります。

## 機能
_未ログイン時_
- 投稿一覧
- 投稿詳細
- ユーザーページ閲覧
- ユーザーのフォロー・フォロワーの閲覧
- 登録・ログイン


_ログインユーザー_
- ユーザープロフィール編集（画像含む）
- 投稿（画像投稿可能）
- ユーザーのフォロー
- 投稿に対する、いいね、コメント
- 投稿、コメントの削除
- ログアウト
