# Teste Irroba

## Instalando
A instalação é feita a partir do docker-compose, para isso, basta executar os seguintes comandos em uma máquina com o docker instalado
`docker-compose build app`
`docker-compose up -d`
Pode ser necessário utilizar do fix para o banco de dados, executando este comando:
`docker-compose exec db mariadb-upgrade -u root -p`
E inserindo a senha.

## Heroku
A API está disponível também no heroku a partir da seguinte url:
https://irrobaschool.herokuapp.com/api

[![Run in Insomnia}](https://insomnia.rest/images/run.svg)](https://insomnia.rest/run/?label=Irroba%20Teste&uri=https%3A%2F%2Fapi.jsonbin.io%2Fb%2F61114805e1b0604017aa2f19)