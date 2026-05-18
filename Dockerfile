# FROM php:8.2-cli

# WORKDIR /app

# COPY . .

# CMD ["php", "./src/index.php"]


FROM php:8.2-cli

WORKDIR /app
COPY . .

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]
