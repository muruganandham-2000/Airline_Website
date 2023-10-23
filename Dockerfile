# Use the official Nginx image as the base image
FROM nginx:latest

# Copy your HTML and CSS files to the web server's root directory
COPY ./HTML /usr/share/nginx/html

# Expose port 80, which is the default HTTP port
EXPOSE 80
