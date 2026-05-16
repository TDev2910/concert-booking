FROM php:8.2-fpm-alpine

# Install system dependencies + supervisor
RUN apk add --no-cache \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    supervisor \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        bcmath \
        zip \
        gd \
        opcache

# Install Redis extension via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create log directory for supervisor
RUN mkdir -p /var/log/supervisor

# Copy supervisor config
COPY ./docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint (source code is NOT copied here — it will be bind-mounted at runtime)
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
