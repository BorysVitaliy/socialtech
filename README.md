SocialTest (test)
=
This application based on the Symfony framework


### Install


```bash
1. cp ./application/.env.example ./application/.env
2. docker-compose build
3. docker-compose up -d
```

##### After installing the utilities Makefile, you can init the application with one command

```bash
make init
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