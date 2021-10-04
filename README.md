## Cron Job Alternative

![Actions Status](https://github.com/takuya/cronjob-alternative/workflows/test%20src/badge.svg)

CRONと同等の処理を、webから管理する

Linuxのcrontabの定期実行の不便さを解消したい。

## CronジョブをWEBから管理。

Cronのジョブをウェブから管理するためのパッケージ。

## 画面

### スケジュール一覧画面
<p align="center">
<img src=https://cdn-ak.f.st-hatena.com/images/fotolife/t/takuya_1st/20210917/20210917182425_original.png width=400/>
</p>

### cron ジョブ定義画面
<p align="center">

<img src='https://cdn-ak.f.st-hatena.com/images/fotolife/t/takuya_1st/20210917/20210917182720_original.png' width=400 />
</p>

### cron ジョブの実行結果画面
<p align="center">

<img src='https://cdn-ak.f.st-hatena.com/images/fotolife/t/takuya_1st/20210917/20210917182531_original.png' width=400 />
</p>

## インストール

インストールは３つの手順です。
- github レポジトリのclone
- 初期設定
- スケジューラー起動

### github からの clone 
プロジェクトをコピーして、WEB領域に展開します。
```
## clone 
git clone git@github.com:takuya/cronjob-alternative.git
cd cronjob-alternative
```
## 初期設定
laravel の初期設定です。
```
## install depends
php composeer.phar install
npm i 
npm run prod
## initialize laravel
cp .env.prod .env 
touch database/database.sqlite
php  artisan storage:link
php  artisan migrate
```
### スケジューラーの起動

スケジューラーを起動してcron実行。
```
sudo php artisan cron:work
```

worker をsudo(root)で動作させると、linuxのcron同じ権限で動作が可能です。

## CRONジョブの登録
CRONジョブは、コマンドから登録できます。

#### cron ジョブの登録例
```
php artisan schedule:cron_add '*/20 * * * *' 'my first cron' 'echo Hello world'
```
## 自動起動設定

インストールと動作確認ができたら、systemd や hypervisordを使って自動起動させます。

### systemd への登録

systemd 用の service unit ファイルを生成し、systemdに登録します。
```
sudo su 
php artisan schedule:systemd_generate > /etc/systemd/system/cron-laravel.servie
systemctl daemon-reload
systemct start cron-laravel
```

## docker での動作

手軽に試せるように docker イメージを用意しました。

### docker での起動サンプル

docker コンテナで動作サンプル
```sh
docker pull ghcr.io/takuya/cronjob-alternative:latest
docker run --rm -p 5000:80 ghcr.io/takuya/cronjob-alternative:latest
```
(DockerHubではなく、github の ghcr.io を利用しています。)

docker起動時のデフォルトユーザー 
```
username: cron@example.com
password: 4jjXBxtRhUmrXBj
```


## このレポジトリについて。

## cron との関係

systemd-cronや /etc/cron の更新はしません。cron 書式を解釈しスケジューラを自分で起動して実行します。

## このソフトの特長

crontabの不便な点を解消したい。

- 実行の一時停止。
- WEBから編集と追加ができる。
- 実行時のエラーログと正常ログを捨てずに保存。
- 実行スケジュールを読みやすくしたい。
- 実行するシェルスクリプトを複数行で書きたい
- 実行ファイルを作成せずにスクリプト本文を書きたい。

<p>
Linuxのcron で実行すると、メール通知が鬱陶しくて標準出力とエラー出力を捨ててしまいがちです。しかしエラーが出てこないのは困ります。
また、何時何分に実行済みか、現在実行中なのかがわからずに困ります。<br>
　そこで、実行後に出力を保存して結果がわかるようにしました。また実行中の場合は実行中がわかるようにしました。
</p>

<p>
cron に書くスクリプトは、１ファイルにまとめるために、何が書かれているのかファイルを見ないとわからなくなります。<br>
そこで、cronジョブに名前をつけてコメント付記できるようにし、ファイルの本文を直接記述して実行できるようにしました。
</p>

<p>
cron のスクリプトを停止するコメントアウトでcrontab ファイルは読みにくくなります。<br>
そこで、実行を一時停止できるようにOn/Offを可能しました。
</p>


## TODO:

- 通知機能設定
- 共通環境変数設定
- ~~dockerビルド~~
- ドキュメント

    