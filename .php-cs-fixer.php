<?php

$header = <<<EOM
This file is part of the TYPO3 project.

(c) 2019-2022 Frank Berger <fberger@sudhaus7.de>

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.

The TYPO3 project - inspiring people to share!
EOM;

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config
    ->setHeader($header, true)
    ->getFinder()
    ->in(__DIR__.'/Classes')
;

return $config;
