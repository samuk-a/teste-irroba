# Teste Irroba

## Instalando
A instalação é feita pelo sail do próprio laravel, para isso, execute o seguinte comando na pasta raíz do projeto:
```./vendor/bin/sail up -d```

Para instalar na máquina localmente, pode-se usar o composer do php, seguindo os comandos:
```
composer install
php artisan migrate
php artisan passport:install
php artisan serve
```

## Heroku
A API está disponível também no heroku a partir da seguinte url:
https://irrobaschool.herokuapp.com/api

[![Run in Insomnia}](https://insomnia.rest/images/run.svg)](https://insomnia.rest/run/?label=Irroba%20Teste&uri=https%3A%2F%2Fapi.jsonbin.io%2Fb%2F61114805e1b0604017aa2f19)