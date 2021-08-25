ARG VERSION

# DEV Image
FROM php:${VERSION}-fpm-alpine AS dev

# System dependencies
RUN apk update && \
	apk add --no-cache --virtual dev-deps git bash autoconf gcc g++ make && \
	apk add --no-cache zlib-dev libzip-dev && \
	set -ex && apk --no-cache add \
	postgresql-dev \
    	$PHPIZE_DEPS && \
	pecl install xdebug && \
	docker-php-ext-enable xdebug && \
	docker-php-ext-install pdo pdo_pgsql zip

# Copy default.ini configuration file
COPY ./docker/php/default.ini /usr/local/etc/php/conf.d/default.ini

# Installing composer & symfony
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
	curl -sS https://get.symfony.com/cli/installer | bash && \
	mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Set environment to dev
ENV APP_ENV=dev

# Set workdir to api so that all the following commands will be executed in this dir
WORKDIR /api



# Copy dev image to test
FROM dev AS test

ENV APP_ENV=test

# Copy all files
COPY . .

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader


FROM dev AS prod

ENV APP_ENV=prod

# Copy all files
COPY . .

# Install composer dependencies without dev dependencies
RUN composer install --no-dev --no-interaction --optimize-autoloader && \
	 rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
	 apk del dev-deps && \
	 rm /usr/local/bin/composer
