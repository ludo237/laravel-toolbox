ROOT_DIR := $(dir $(realpath $(lastword $(MAKEFILE_LIST))))

directories:
	rm -f $(ROOT_DIR)bootstrap/cache/*.php
	rm -rf $(ROOT_DIR)storage/logs/*
	rm -rf $(ROOT_DIR)storage/app/public/*
	rm -rf $(ROOT_DIR)storage/framework/testing/*

docker_up:
	echo "Bootstrapping docker environment and app"
	yes | cp -rf $(ROOT_DIR)docker/docker-compose.development.yml $(ROOT_DIR)docker-compose.yml
	yes | cp -rf $(ROOT_DIR).env.example $(ROOT_DIR).env
	docker run --rm --tty --interactive --volume $(ROOT_DIR):/app registry.gitlab.com/6go/dx/docker/composer:latest composer install --ignore-platform-req=ext-intl --ignore-platform-req=ext-gd
	docker-compose up -d --build --force-recreate
	docker exec -it php apk add mysql-client make ffmpeg
	echo "Docker containers are ready"

docker_down:
	echo "Destroy docker environment"
	docker-compose down --volumes

laravel_init: directories
	echo "Bootstrapping development environment"
	cp $(ROOT_DIR).env.example .env
	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan optimize:clear
	echo "Laravel is ready"

test_init: directories
	echo "Bootstrapping PHPUnit environment"
	mkdir -p $(ROOT_DIR)reports
	mkdir -p $(ROOT_DIR)reports/phpunit/coverage
	touch $(ROOT_DIR)reports/phpunit/coverage/teamcity.txt
	php -r "file_exists('.env') || copy('./envs/.env.dev', '.env');"
	php artisan optimize:clear
	php artisan migrate:fresh --env=testing
	echo "Tests are ready"

test_coverage: test_init
	php artisan test \
		--parallel \
		--processes=6 \
		--coverage-html ./reports/phpunit/coverage/html \
		--env=testing

test:
	php artisan test

test_fast:
	php artisan test --parallel --processes=6

audit:
	composer audit

phpstan:
	./vendor/bin/phpstan analyse --error-format gitlab --memory-limit=256M

pint:
	./vendor/bin/pint

pint_dry:
	./vendor/bin/pint --format=gitlab --test

rector:
	./vendor/bin/rector --output-format=json
