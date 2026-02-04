FROM php:8.1-cli

# Instalar extensión PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar archivos del proyecto
COPY . /app
WORKDIR /app

# Exponer puerto
EXPOSE 8080

# Comando para iniciar el servidor
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t api/"]
