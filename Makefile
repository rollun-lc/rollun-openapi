init: docker-down-clear docker-pull docker-build docker-up composer-install
up: docker-up
down: docker-down
restart: docker-down docker-up
test: composer-test
development-enable: composer-development-enable
development-disable: composer-development-disable

regenerate-examples:
	docker compose run --rm php-openapi-generator php bin/openapi-generator generate:client -m ./openapi.yaml
	docker compose run --rm php-openapi-generator php bin/openapi-generator generate:client -m ./openapi-task.yaml

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

composer-install:
	docker compose exec rollun-openapi-php-fpm composer install

composer-development-enable:
	docker compose exec rollun-openapi-php-fpm composer development-enable

composer-development-disable:
	docker compose exec rollun-openapi-php-fpm composer development-disable

composer-test:
	docker compose exec rollun-openapi-php-fpm composer test

logstash-logs:
	docker compose logs -f -t rollun-openapi-logstash

openapi-generate-server:
	docker compose run --rm php-openapi-generator php bin/openapi-generator generate:server --arrayConverting=dataTransfer

openapi-generate-client:
	docker compose run --rm php-openapi-generator php bin/openapi-generator generate:client
