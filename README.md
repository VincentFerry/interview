# Readme

## Install

Ensure you have PHP 8.4 and sqlite extension on your pc.

```shell
composer install
bin/console doctrine:migrations:migrate -n
bin/console doctrine:fixtures:load -n
```

## Run

```shell
symfony serve # if you have the symfony binary
```

```shell
php -S localhost:8000 -t public/ # if you only have php
```

## Test

```shell
bin/console doctrine:migrations:migrate -ne test
bin/console doctrine:fixtures:load -ne test
bin/phpunit
```
