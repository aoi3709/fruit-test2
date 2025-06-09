FROM php:8.3-fpm

COPY php.ini /usr/local/etc/php/

# 必要なシステムライブラリとツールをインストール
RUN apt update \
    && apt install -y --no-install-recommends \
        default-mysql-client \
        unzip \
        git \
        # PHP拡張機能のビルドに必要なシステムライブラリ
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
    && rm -rf /var/lib/apt/lists/* # aptキャッシュをクリーンアップ

# PHP拡張機能をインストール
# -j$(nproc) はビルドを高速化
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    zip \
    mbstring \
    dom \
    curl \
    intl \
    ; \
# gd 拡張機能のコンパイルに必要なconfigure引数
docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd \
; # 最後のコマンドがセミコロンで終わるように

# Composer のインストール部分は変更なし
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update

WORKDIR /var/www