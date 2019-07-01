# zaim-cli

Web 版 Zaim をスクレイピングして、家計簿の入力履歴を表示するコマンドラインツール。

## 動作環境

PHP 7.1+

## インストール

```console
$ git clone git@github.com:yuuan/zaim-cli.git zaim-cli
$ cd $_
$ composer install
$ cp .env.example .env
```

## 設定

Zaim へのログインに使用するメールアドレスとパスワードを `.env` に設定。

```sh
ZAIM_AUTH_EMAIL=email@example.com
ZAIM_AUTH_PASSWORD=p@ssword!
```

## 実行

```sh
$ php zaim-cli money:get
```

または

```sh
$ php zaim-cli money:get --month=201906
```

## テスト

```sh
$ ./vendor/bin/phpunit
```

[![CircleCI](https://circleci.com/gh/yuuan/zaim-cli.svg?style=svg)](https://circleci.com/gh/yuuan/zaim-cli)

## ライセンス

[MIT license](https://github.com/yuuan/zaim-cli/blob/master/LICENSE.md).
