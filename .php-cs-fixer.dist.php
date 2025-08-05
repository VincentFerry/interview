<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PSR12'      => true,
        '@Symfony'    => true,
    ])
    ->setFinder($finder);
