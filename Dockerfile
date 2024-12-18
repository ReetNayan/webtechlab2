# Use an official PHP image
FROM php:8.1-cli

# Set the working directory
WORKDIR /app

# Copy project files into the container
COPY . /app

# Expose the port Render will use
EXPOSE 80

# Start the built-in PHP server
CMD ["php", "-S", "0.0.0.0:80", "-t", "."]
