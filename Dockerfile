# Use an official Nginx image as the base image
FROM nginx:alpine

# Copy your HTML file to the default Nginx web root directory
COPY HTML/index.html /usr/share/nginx/html/
