# Use an official PHP runtime as a parent image
FROM php:8.0-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the contents of your PHP application to the container
COPY . /var/www/html

# Install any additional PHP extensions or dependencies if needed
# For example, if you need to install PDO for database connectivity:
# RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80 for Apache
EXPOSE 80

# Start the Apache web server when the container starts
CMD ["apache2-foreground"]
