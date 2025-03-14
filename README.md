# Extendable Order and Payment Management API
a Laravel-based API for managing orders and payments, with a focus on clean code
principles and extensibility. The system should allow adding new payment gateways with
minimal effort.

usign Repository Desing Pattern for modular structure <br />
it depends on Strategy Design Pattern for using different payment gateways.

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

pagination size depends on PER_PAGE please add it inside .env file
```
PER_PAGE=15
```

## - Change Pending Orders

Reserved order products qty could be back to stock if there's no any success payment assigned to order, it depends on the minutes interval you spcified in .env <br /> 
in Conosle/Commands you'll find Command called ChangePendingOrderStatus make sure to schedule it on your cronjob.

```
BACK_PRODUCT_DURATION=15
```

# Payment Gateways Extensibility
We'll implement the strategy pattern to allow easy integration of new payment gateways. <br />
first of all you have to add payment gateway configuration inside .env file based on test & live modes <br />
add your payment gateway name in supported payments array inside config/app.php
```
....
'supported_payments' => ['credit_card', 'paypal', 'cash', 'wallet','knet','upayment','your_new_payment_name'],
...

```
