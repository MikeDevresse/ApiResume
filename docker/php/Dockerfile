ARG VERSION

# DEV Image
FROM php:${VERSION}-fpm-alpine AS dev


# System dependencies
RUN apk add --no-cache --virtual dev-deps git; \
	set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		zlib-dev \
		libzip-dev \
		postgresql-dev \
	; \
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		pdo_pgsql \
		zip \
	; \
	docker-php-ext-enable \
		pdo_pgsql \
		zip \
	; \
	pecl install xdebug; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps

# Copy default.ini configuration file
COPY ./docker/php/default.ini /usr/local/etc/php/conf.d/default.ini

# Installing composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

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
