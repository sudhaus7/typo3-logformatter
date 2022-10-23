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

interface PatternInterface
{
    /**
     * Returns the preg pattern for this class
     *
     * @return string
     */
    public function getPattern(): string;

    /**
     * Returns the matches
     * @return string[]
     */
    public function getMatches(string $line): array;
}
