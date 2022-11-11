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

$myversion = substr(\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()), 0, 5);

$config = [
    '11005'=>[
        'target' => \Sudhaus7\Logformatter\MiddleWares\LogrequesturlMiddleWare::class,
        'before'  => [
            'typo3/cms-core/normalized-params-attribute',
        ],
    ],
    'default'=>[
        'target' => \Sudhaus7\Logformatter\MiddleWares\LogrequesturlMiddleWare::class,
        'before'  => [
            'normalized-params-attribute',
        ],
    ],
];
$usedconfig =  $config[$myversion] ?? $config['default'];
return [
    'frontend' => [
        'sudhaus7/logformatter/addrequesturltolog' => $usedconfig,
    ],
    'backend'  => [
        'sudhaus7/logformatter/addrequesturltolog' => $usedconfig,
    ],
];
