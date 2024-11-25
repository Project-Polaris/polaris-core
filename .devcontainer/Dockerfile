FROM php:8.3-cli-bookworm

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev dnsutils librsvg2-bin fswatch ffmpeg nano vim  \
    && curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc \
    && chmod a+r /etc/apt/keyrings/docker.asc \
    && echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/debian $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | tee /etc/apt/sources.list.d/docker.list \
    && apt-get update \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && apt-get install -y libsodium-dev libmemcached-dev libssl-dev zlib1g-dev docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin postgresql-common \
    && YES=yes /usr/share/postgresql-common/pgdg/apt.postgresql.org.sh \
    && apt-get install -y postgresql-client-17 libpq-dev \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pecl install xdebug \
    && pecl install redis \
    && pecl install memcached \
    && docker-php-ext-configure sodium \
    && docker-php-ext-install sodium \
    && docker-php-ext-configure pcntl \
    && docker-php-ext-install pcntl \
    && docker-php-ext-configure pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-enable xdebug redis memcached

RUN setcap "cap_net_bind_service=+ep" /usr/local/bin/php

COPY php.ini /usr/local/etc/php/conf.d/99-www.ini

ENV APP_ENV=local

EXPOSE 80/tcp

CMD ["/usr/local/bin/php", "/var/www/html/artisan", "serve", "--host=0.0.0.0", "--port=80"]
