# Use the official PHP image as the base image
FROM php:latest

# Set the working directory inside the container
WORKDIR /var/www/html

# Install PDO extension for database connectivity
RUN docker-php-ext-install pdo pdo_mysql

# Copy all the PHP files and other necessary files to the container
COPY . /var/www/html

# Expose port 80 for HTTP access (adjust if your PHP server listens on a different port)
EXPOSE 80

# Start the PHP server when the container is launched
CMD ["php", "-S", "0.0.0.0:80"]
