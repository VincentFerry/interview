<?php

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;

#[AsTask(description: 'Run PHPStan')]
function phpstan(): void
{
    io()->title('Running PHPStan...');
    run('vendor/bin/phpstan');
}

#[AsTask(description: 'Run PHP-CS-Fixer')]
function cs(): void
{
    io()->title('Running PHP-CS-Fixer...');
    run('vendor/bin/php-cs-fixer fix');
}

#[AsTask(description: 'Load database fixtures')]
function fixtures(): void
{
    io()->title('Loading database fixtures...');
    run('php bin/console doctrine:fixtures:load --no-interaction');
}

#[AsTask(description: 'Install project')]
function install(): void
{
    io()->title('Installing project...');
    run('composer install');
    run('php bin/console doctrine:database:create');
    run('php bin/console doctrine:migrations:migrate -n');
    run('php bin/console doctrine:fixtures:load -n');
}

#[AsTask(description: 'Run tests')]
function test(): void
{
    io()->title('Running tests...');
    run('php bin/console doctrine:database:drop --force -e test');
    run('php bin/console doctrine:database:create -e test');
    run('php bin/console doctrine:migrations:migrate -n -e test');
    run('php bin/console doctrine:fixtures:load -n -e test');
    run('vendor/bin/phpunit');
}
