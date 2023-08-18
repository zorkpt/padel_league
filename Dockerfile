FROM php:8-fpm

# Instalar dependências e extensões necessárias
RUN apt-get update && apt-get install -y libpng-dev zip unzip
RUN docker-php-ext-install pdo pdo_mysql gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar apenas o arquivo composer.json e composer.lock
COPY composer.lock composer.json /var/www/html/

# Instalar dependências do projeto
RUN composer install --prefer-dist --no-scripts --no-autoloader --no-interaction

# Copiar o resto dos arquivos
COPY . .

# Ajustar as permissões e executar o autoloader do Composer
RUN chown -R www-data:www-data /var/www/html && composer dump-autoload --optimize
