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

class Typo3LogPattern implements PatternInterface
{
    /**
     * @var string
     */
    private $pattern = '/(?P<tstamp>\w+, \d+ \w+ \d{4} \d{2}:\d{2}:\d{2}\s+\+\d{4})\s+\[(?P<level>\w+)\]\s+request="(?P<request>.+)"\s+component="(?P<component>.+)":\s+(?P<msg>.+)(\s+-\s+(?P<json>\{.*\})?)/';

    /**
     * @inheritDoc
     */
    public function getMatches(string $line): array
    {
        $matches = [];
        preg_match($this->getPattern(), $line, $matches);
        return  $matches;
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }
}
