<?php

declare(strict_types=1);

# Config Reference: https://mlocati.github.io/php-cs-fixer-configurator/#version:3.13
return function (iterable $finder): PhpCsFixer\Config {
    $config = new PhpCsFixer\Config();
    $config->setFinder($finder);
    $config->setRiskyAllowed(true);
    $config->setRules(
        [
            '@PSR12'               => true,
            'strict_param'         => true,
            'strict_comparison'    => true,
            'declare_strict_types' => true,
            'void_return'          => true,
            'array_syntax'         => ['syntax' => 'short'],
            'braces'               => [
                'allow_single_line_closure' => true,
            ],
            'function_declaration' => [
                'closure_fn_spacing'       => 'none',
                'closure_function_spacing' => 'one',
            ],
        ]
    );

    return $config;
};
