<?php

declare(strict_types=1);

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

namespace Sudhaus7\Logformatter\Traits;

use Sudhaus7\Logformatter\Interfaces\FormatInterface;
use Sudhaus7\Logformatter\Interfaces\PatternInterface;

trait TestclassTrait
{
    public function checkConfig(string $keyToCheck): bool
    {
        /**
         * @psalm-suppress MixedArrayAccess
         */
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']) && is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']) && !empty($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter'])) {
            if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter'][$keyToCheck])) {
                return true;
            }
        }
        return false;
    }

    public function checkIfClassExistsAndImplementsFormatInterface(string $className): bool
    {
        if (class_exists($className) && is_array(class_implements($className)) && in_array(FormatInterface::class, class_implements($className))) {
            return true;
        }
        return false;
    }
    public function checkIfClassExistsAndImplementsPatternInterface(string $className): bool
    {
        if (class_exists($className) && is_array(class_implements($className)) && in_array(PatternInterface::class, class_implements($className))) {
            return true;
        }
        return false;
    }
}
