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

namespace Sudhaus7\Logformatter\Pattern;

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
