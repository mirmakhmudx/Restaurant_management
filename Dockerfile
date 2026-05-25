FROM php:8.3-fpm-alpine

# ── System Dependencies ───────────────────────────────
RUN apk update && apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    nodejs \
    npm

# ── PHP Extensions ────────────────────────────────────
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    mbstring \
    bcmath \
    intl \
    pcntl \
    opcache

# ── Redis Extension ───────────────────────────────────
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# ── Composer ──────────────────────────────────────────
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# ── Working Directory ─────────────────────────────────
WORKDIR /var/www/html

# ── Permissions ───────────────────────────────────────
RUN addgroup -g 1000 biteplate && \
    adduser -u 1000 -G biteplate -s /bin/sh -D biteplate && \
    chown -R biteplate:biteplate /var/www/html

USER biteplate

EXPOSE 9000

CMD ["php-fpm"]
