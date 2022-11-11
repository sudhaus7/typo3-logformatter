<?php

/*
 * This file is part of the TYPO3 project.
 *
 * @author Frank Berger <fberger@sudhaus7.de>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

$EM_CONF['logformatter'] = [
    'title' => '(Sudhaus7) Logformatter',
    'description' => 'A CLI tool to format and search TYPO3 Logfiles',
    'category' => 'module',
    'version' => '1.4.2',
    'state' => 'stable',
    'clearcacheonload' => 1,
    'author' => 'Frank Berger',
    'author_email' => 'fberger@sudhaus7.de',
    'author_company' => 'Sudhaus7, ein Label der B-Factor GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-11.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Sudhaus7\\Logformatter\\' => 'Classes',
        ],
    ],
];
