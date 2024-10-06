IMAGE_NAME = snakesandladders

# Objectiu per defecte que fa build i executa composer
default: composer

# Executa Composer després de construir la imatge
composer: build
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) composer install

# Objectiu per construir la imatge Docker
build:
	docker build -t $(IMAGE_NAME) .

# Executa l'script PHP
run:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) php game.php

# Executa els tests Behat
test:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) bin/behat
