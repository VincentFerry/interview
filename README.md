# Readme

## Install

Ensure you have PHP 8.4 and sqlite extension on your pc.

```shell
composer install
bin/console doctrine:migrations:migrate -n
bin/console doctrine:fixtures:load -n
```

## Run

If you have Symfony binary: `symfony serve`, else `php -S localhost:8000 -t public/`.
