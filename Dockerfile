# Use the official Nginx image as the base image
FROM nginx:latest

# Copy your HTML files to the Nginx default web directory
COPY HTML/index.html /usr/share/nginx/html

# Expose port 80, which is the default HTTP port
EXPOSE 80
