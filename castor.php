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
