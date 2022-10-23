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

namespace Sudhaus7\Logformatter\Interfaces;

use Symfony\Component\Console\Output\OutputInterface;

interface FormatInterface
{
    /**
     * Returns the Symfony COmmand Output compatible Format
     * @return string
     */
    public function getFormat(): string;

    /**
     * Do the actual formatting
     *
     * @param string[] $matches matches Produced by a Sudhaus7\Logformatter\Pattern\PatternInterface
     * @param string|null $filename Optional Filename of the Logfile
     *
     * @return string
     */
    public function formatLine(array $matches, string $filename = null): string;

    /**
     * This can be used to add style marker to the Symfony's output object
     * @param OutputInterface $output
     */
    public function configOutput(OutputInterface $output): void;
}
