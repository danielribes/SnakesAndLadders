IMAGE_NAME = snakesandladders

# Default action: show help
default: help

## build: Build Docker image and install Composer dependencies
build:
	docker build -t $(IMAGE_NAME) .
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) composer install

## run: Run the PHP script, plays automatically until winning
run:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) php game.php

## run-bysteps: Run the PHP script interactively step by step
run-bysteps:
	docker run --rm -it -v $(PWD):/app -w /app $(IMAGE_NAME) php game.php --bysteps

## test: Run Behat tests
test:
	docker run --rm -v $(PWD):/app -w /app $(IMAGE_NAME) bin/behat

## help: Show this help message
help:
	@echo "SnakesAndLadders - Available commands:"
	@echo ""
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' | sed -e 's/^/ /'