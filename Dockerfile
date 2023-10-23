# Use an official Apache image as the base image
FROM php:7.4-apache

# Set environment variables for PHP
ENV PHP_ERROR_REPORTING E_ALL
ENV PHP_DISPLAY_ERRORS On

# Copy the contents of the current directory to the web server's root directory
COPY . /var/www/html

# Expose port 80, which is the default HTTP port
EXPOSE 80

# Start the Apache web server
CMD ["apache2-foreground"]
