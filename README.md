# Recipe App

A Symfony application for managing recipes.

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

## Scripts

Install castor locally or globally: https://castor.jolicode.com/installation/
Now, you can run these commands:

```shell
castor phpstan
castor cs
castor fixtures
```

## Todo

- [ ] Add a "favorite" button on the "show" page.
- [ ] Display the calories of each recipe on the "show" page, using the [nutritionix api natural language processing](https://www.nutritionix.com/natural-demo).
- [ ] Fix the phpstan errors
