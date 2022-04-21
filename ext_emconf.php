<?php

$EM_CONF['logformatter'] = [
    'title' => '(Sudhaus7) Logformatter',
    'description' => 'A CLI tool to format and search TYPO3 Logfiles',
    'category' => 'module',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Frank Berger',
    'author_email' => 'fberger@sudhaus7.de',
    'author_company' => 'Sudhaus7, ein Label der B-Factor GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-11.5.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Sudhaus7\\Logformatter\\' => 'Classes',
        ]
    ],
];

