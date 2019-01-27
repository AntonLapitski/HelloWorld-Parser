include docker/.env-dev

.PHONY: build
build: permissions up composer-install

.PHONY: permissions
permissions:
	@sudo chmod -R 777 app/public

.PHONY: down
down:
	@docker-compose -f docker/docker-compose-dev.yml down

.PHONY: up
up: down
	@docker-compose -f docker/docker-compose-dev.yml build
	@docker-compose -f docker/docker-compose-dev.yml up -d

.PHONY: composer-install
composer-install:
	@docker exec -it web bash -c "composer install -o -n"

.PHONY: shell
shell:
	@docker exec -it web bash -c "export COLUMNS=`tput cols`; export LINES=`tput lines`; exec bash"

.PHONY: logs
logs:
	@docker-compose -f docker/docker-compose-dev.yml logs -f -t

.PHONY: browser
browser:
	@xdg-open $(APP_HOST):$(APP_PORT)

