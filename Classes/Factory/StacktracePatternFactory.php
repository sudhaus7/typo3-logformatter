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

use Sudhaus7\Logformatter\Interfaces\PatternInterface;
use Sudhaus7\Logformatter\Pattern\StacktracePattern;
use Sudhaus7\Logformatter\Traits\TestclassTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class StacktracePatternFactory
{
    use TestclassTrait;

    public function get(): PatternInterface
    {
        $pattern = null;
        if ($this->checkConfig('stacktracePattern')) {
            /** @var string $className */
            $className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['stacktracePattern'];
            if ($this->checkIfClassExistsAndImplementsInterface($className)) {
                /** @var \Sudhaus7\Logformatter\Interfaces\PatternInterface $pattern */
                $pattern = GeneralUtility::makeInstance($className);
            }
        }

        if (getenv('LOGFORMATTER_STACKTRACEPATTERN')) {
            $className = str_replace('.', '\\', getenv('LOGFORMATTER_STACKTRACEPATTERN'));
            if ($this->checkIfClassExistsAndImplementsInterface($className)) {
                /** @var \Sudhaus7\Logformatter\Interfaces\PatternInterface $pattern */
                $pattern = GeneralUtility::makeInstance($className);
            }
        }

        if ($pattern === null) {
            $pattern = GeneralUtility::makeInstance(StacktracePattern::class);
        }
        return $pattern;
    }
}
