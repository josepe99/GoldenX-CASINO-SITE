FROM node:16-bullseye

RUN apt-get update && apt-get install -y \
    python3 \
    make \
    g++ \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
