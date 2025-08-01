FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    pkg-config \
    autoconf \
    build-essential \
    libsqlite3-0 \
    sqlite3-dev \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set environment variables for SQLite3
ENV SQLITE_CFLAGS="-I/usr/include/sqlite3"
ENV SQLITE_LIBS="-L/usr/lib -lsqlite3"

# Install PHP extensions one by one to avoid conflicts
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy existing application directory contents
COPY . /var/www

# Create database directory and file
RUN mkdir -p /var/www/database
RUN touch /var/www/database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap/cache
RUN chmod 664 /var/www/database/database.sqlite

# Create startup script
RUN echo '#!/bin/bash' > /start.sh && \
    echo 'set -e' >> /start.sh && \
    echo 'echo "Starting Laravel application..."' >> /start.sh && \
    echo 'if [ ! -f .env ]; then' >> /start.sh && \
    echo '  echo "Creating .env file..."' >> /start.sh && \
    echo '  cp .env.example .env' >> /start.sh && \
    echo 'fi' >> /start.sh && \
    echo 'if [ ! -f database/database.sqlite ]; then' >> /start.sh && \
    echo '  echo "Creating database file..."' >> /start.sh && \
    echo '  touch database/database.sqlite' >> /start.sh && \
    echo 'fi' >> /start.sh && \
    echo 'chmod 664 database/database.sqlite' >> /start.sh && \
    echo 'echo "Generating application key..."' >> /start.sh && \
    echo 'php artisan key:generate --force' >> /start.sh && \
    echo 'echo "Running migrations..."' >> /start.sh && \
    echo 'php artisan migrate --force' >> /start.sh && \
    echo 'echo "Starting server on port $PORT..."' >> /start.sh && \
    echo 'php artisan serve --host=0.0.0.0 --port=$PORT' >> /start.sh && \
    chmod +x /start.sh

EXPOSE 8000
CMD ["/start.sh"] 