<?php

/*
 * This file is part of the TYPO3 project.
 *
 * (c) 2019-2022 Frank Berger <fberger@sudhaus7.de>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

return [
    'frontend' => [
        'logformatter:addrequesturltolog' => [
            'target' => \Sudhaus7\Logformatter\MiddleWares\LogrequesturlMiddleWare::class,
            'after'  => [
                'normalized-params-attribute',
            ],
        ],
    ],
    'backend'  => [
        'logformatter:addrequesturltolog' => [
            'target' => \Sudhaus7\Logformatter\MiddleWares\LogrequesturlMiddleWare::class,
            'after'  => [
                'normalized-params-attribute',
            ],
        ],
    ],
];
