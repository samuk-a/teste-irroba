# Teste Irroba

## Instalando
A instalação é feita a partir do docker-compose, para isso, basta executar os seguintes comandos em uma máquina com o docker instalado
`docker-compose build app`
`docker-compose up -d`
Pode ser necessário utilizar do fix para o banco de dados, executando este comando:
`docker-compose exec db mariadb-upgrade -u root -p`
E inserindo a senha.
Depois de finalizado, 