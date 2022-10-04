start:
	docker-compose up -d web

composer:
	docker-compose run --rm web bash -c 'composer self-update && composer install'

composerupdate:
	docker-compose run --rm web bash -c 'composer self-update && composer update'
