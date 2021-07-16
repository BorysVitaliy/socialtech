up: docker-up
down: docker-down
restart: docker-down docker-up
init: env docker-down-clear docker-pull docker-build docker-up perm jwt

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

app-composer-install:
	docker-compose run --rm app-php-cli composer install

perm:
	sudo chmod -R 755 ./application/var/storage/
	sudo chmod -R 777 ./storage/

env:
	cp ./application/.env.example ./application/.env

jwt:
	mkdir ./application/config/jwt
	openssl genrsa -out ./application/config/jwt/private.pem -aes256 4096
	openssl rsa -pubout -in ./application/config/jwt/private.pem -out ./application/config/jwt/public.pem
	chmod 644 ./application/config/jwt/public.pem ./application/config/jwt/private.pem