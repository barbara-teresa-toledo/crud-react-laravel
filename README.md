# crud-react-laravel

CRUD de clientes utilizando React e Laravel.

## Montando o back-end

1. Utilizar o script para criação do banco de dados que será utilizado, está no arquivo `database-crud-react-laravel.sql`
2. Ir no diretório `crud-react-laravel`
3. Copiar o arquivo `env.example` e renomear a sua cópia para `.env`
4. Execute os comandos em sequência

```
composer install
php artisan migrate
php artisan serve
```

## Montando o front-end

1. Ir no diretório `crud-react`
2. Executar o comando

```
npm install && npm run start
```
