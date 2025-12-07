CONTAINER_NAME=php

# OS ごとに script を使い分ける
ifeq ($(shell uname -s),Darwin)
	PINT_SCRIPT = script /dev/null docker compose exec $(CONTAINER_NAME) ./vendor/bin/pint
else
	PINT_SCRIPT = script -q -c "docker compose exec $(CONTAINER_NAME) ./vendor/bin/pint" /dev/null
endif

build-xdebug:
	docker build -t xdebug-builder ./docker-containers/build-xdebug
	docker run --rm \
		--user $(shell id -u):$(shell id -g) \
		-v "$(PWD)/docker-containers/php/provisioning/:/output" \
		xdebug-builder

up: build-xdebug
	@if [ ! -f ./.env ]; then \
		echo "Copying .env.example to .env"; \
		cp .env.example .env; \
	else \
		echo ".env already exists"; \
	fi

	@if [ ! -f ./code/.env ]; then \
		echo "Copying ./code/.env.example to ./code/.env"; \
		cp ./code/.env.example ./code/.env; \
	else \
		echo "./code/.env already exists"; \
	fi

	@if [ ! -f ./code/.env.testing ]; then \
		echo "Copying ./code/.env.testing.example to ./code/.env.testing"; \
		cp ./code/.env.testing.example ./code/.env.testing; \
	else \
		echo "./code/.env.testing already exists"; \
	fi

	docker compose build
	docker compose up -d

	echo "Installing Composer dependencies..."
	docker compose exec $(CONTAINER_NAME) composer install

	echo "Generating Laravel app key..."
	docker compose exec $(CONTAINER_NAME) php artisan key:generate
	docker compose exec $(CONTAINER_NAME) php artisan key:generate --env=testing

test:
	docker compose exec $(CONTAINER_NAME) ./vendor/bin/pest --colors=always $(foreach path,$(filter-out $@,$(MAKECMDGOALS)),$(subst code/,,$(path)))

pest:
	docker compose exec $(CONTAINER_NAME) ./vendor/bin/pest --colors=always $(foreach path,$(filter-out $@,$(MAKECMDGOALS)),$(subst code/,,$(path)))

pint:
	$(PINT_SCRIPT) $(foreach path,$(filter-out $@,$(MAKECMDGOALS)),$(subst code/,,$(path)))
