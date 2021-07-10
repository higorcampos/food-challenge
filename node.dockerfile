FROM node:latest

USER root
RUN apt update && apt install -y build-essential autoconf curl libudev-dev git  && \
    npm -g config set user root

# Add user for laravel application
RUN groupadd -g 1001 www
RUN useradd -u 1001 -ms /bin/bash -g www www

# Change current user to www
USER www