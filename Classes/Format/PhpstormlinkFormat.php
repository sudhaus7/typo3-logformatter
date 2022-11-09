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

namespace Sudhaus7\Logformatter\Format;

class PhpstormlinkFormat extends FilelinkFormat
{
    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return '%s <href=phpstorm://open?file=%2$s&line=%3$s>%2$s</>(%3$s): %4$s';
    }
}
