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

## Todo

- [ ] Add a "favorite" button on the "show" page.
- [ ] Display the calories of each recipe on the "show" page, using the nutritionix api (natural language processing endpoint).
