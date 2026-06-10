<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/catalog',
        __DIR__ . '/admin',
        __DIR__ . '/system',
        __DIR__ . '/themes',
        __DIR__ . '/install',
    ])
    ->name('*.php')
    ->exclude([
        'view/javascript/ckeditor',
        'view/javascript/jquery',
        'view/javascript/summernote',
    ])
    ->notPath([
        'view/javascript/common.js',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                             => true,
        'array_syntax'                       => ['syntax' => 'short'],
        'no_unused_imports'                  => true,
        'ordered_imports'                    => ['sort_algorithm' => 'alpha'],
        'no_extra_blank_lines'               => true,
        'trailing_comma_in_multiline'        => true,
        'single_quote'                       => true,
        'no_whitespace_in_blank_line'        => true,
        'blank_line_after_namespace'         => true,
        'object_operator_without_whitespace' => true,
        'binary_operator_spaces'             => true,
        'cast_spaces'                        => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
