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

use Sudhaus7\Logformatter\Interfaces\FormatInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

class FilelinkFormat implements FormatInterface
{
    /**
     * @inheritDoc
     */
    public function formatLine(array $matches, string $filename = null): string
    {
        return sprintf($this->getFormat(), $matches['index'], $matches['file'], $matches['linenumber'], $matches['msg']);
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return '%s <href=file://%2$s>%2$s</>(%3$s): %4$s';
    }

    /**
     * @inheritDoc
     */
    public function configOutput(OutputInterface $output): void {}
}
