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

	docker compose up -d
