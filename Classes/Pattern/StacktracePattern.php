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

namespace Sudhaus7\Logformatter\Pattern;

use Sudhaus7\Logformatter\Interfaces\PatternInterface;

class StacktracePattern implements PatternInterface
{
    /**
     * @inheritDoc
     */
    public function getMatches(string $line): array
    {
        $m = [];
        preg_match($this->getPattern(), $line, $m);
        return $m;
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return '/(?P<index>#\d+)\s+(?P<file>.+)\((?P<linenumber>\d+)\):\s+(?P<msg>.+)/';
    }
}
