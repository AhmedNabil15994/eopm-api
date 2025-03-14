# Extendable Order and Payment Management API
a Laravel-based API for managing orders and payments, with a focus on clean code
principles and extensibility. The system should allow adding new payment gateways with
minimal effort.

# Setup
first run composer install to bring all packages

```
composer install
```

run the following command to install jwt authentication <br />
```
php artisan jwt:secret
```

edit your env file with database connection configuration <br />
then run migrate command
```
php artisan migrate
```

then seed database with ready seeders <br />
```
php artisan db:seed
```
