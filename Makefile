default: composer

# Executa Composer
composer:
	docker run --rm -v $(PWD):/app -w /app snakes1 composer install

# Executa l'script PHP
run:
	docker run --rm -v $(PWD):/app -w /app snakes1 php game.php

# Executa els tests Behat
test:
	docker run --rm -v $(PWD):/app -w /app snakes1 bin/behat