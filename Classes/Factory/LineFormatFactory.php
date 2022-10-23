<?php

declare(strict_types=1);

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

namespace Sudhaus7\Logformatter\Factory;

use Sudhaus7\Logformatter\Format\LineFormat;
use Sudhaus7\Logformatter\Interfaces\FormatInterface;
use Sudhaus7\Logformatter\Traits\TestclassTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LineFormatFactory
{
    use TestclassTrait;

    public function get(): FormatInterface
    {
        $format = null;
        if ($this->checkConfig('format')) {
            /** @var string $className */
            $className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['format'];
            if ($this->checkIfClassExistsAndImplementsFormatInterface($className)) {
                /** @var \Sudhaus7\Logformatter\Interfaces\FormatInterface $format */
                $format = GeneralUtility::makeInstance($className);
            }
        }

        if (\is_string(getenv('LOGFORMATTER_LINEFORMATTER'))) {
            $className = str_replace('.', '\\', (string)getenv('LOGFORMATTER_LINEFORMATTER'));
            if ($this->checkIfClassExistsAndImplementsFormatInterface($className)) {
                /** @var \Sudhaus7\Logformatter\Interfaces\FormatInterface $format */
                $format = GeneralUtility::makeInstance($className);
            }
        }

        if ($format === null) {
            $format = GeneralUtility::makeInstance(LineFormat::class);
        }
        return $format;
    }
}
