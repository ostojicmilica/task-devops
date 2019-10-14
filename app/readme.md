## Currency Exchange App
This software, will create a service to take care of currency exchange rates provided by the https://exchangeratesapi.io/. Store data of currency rates and provide it
               via XML-RPC and REST API. Notify other services if exchange rates for USD to EUR is changed using Rabbit MQ.

## Installation

- Copy file .env.example to .env


- Install project dependencies


```
composer install
```

- Run migration

```
php artisan migrate
```



- Generate fake data to see application features

```
php artisan db:seed
```


- Check the service online at http://localhost:8181



## Server Requirements

- PHP >= 7.1.3
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- sqlite PHP Extention

 

## Framework

Lumen 6.1



## Author

Soheila Behyari, soheila.behyari@gmail.com
