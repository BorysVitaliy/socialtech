SocialTest (test)
=
This application based on the Symfony framework


### Install

##### If you have the utilities Makefile, you can init the application with one command

```bash
make init
Enter pass phrase for config/jwt/private.pem to .env
```
###or
```bash
1. cp ./application/.env.example ./application/.env
2. docker-compose build
3. docker-compose up -d
4. mkdir ./application/config/jwt
5. openssl genrsa -out ./application/config/jwt/private.pem -aes256 4096
6. openssl rsa -pubout -in ./application/config/jwt/private.pem -out ./application/config/jwt/public.pem
7. chmod 644 ./application/config/jwt/public.pem ./application/config/jwt/private.pem
8. Enter pass phrase for config/jwt/private.pem to .env
9. sudo chmod -R 755 ./application/var/storage/
```


See Rest Full Api http://localhost:8081
#### Swagger docs
URL : http://localhost:8081/docs/

### RabbitMQ UI
URL : http://localhost:15672/#/
``username:guest , password:guest``
#### Console Command
Generate Swagger docs
```
docker-compose exec app-php-fpm php bin/console api:docs
```

### Дополнение
Для того, чтобы трекать действия не авторизованного пользователя, необходимо создать ему uuid (с помощью роута - /api/anonymous/create),
затем этот uuid передавать в заголовке с именем x-social-uuid на каждом из следующих запросов.