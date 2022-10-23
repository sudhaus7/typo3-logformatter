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

namespace Sudhaus7\Logformatter\Format;

use InvalidArgumentException;
use Sudhaus7\Logformatter\Interfaces\FormatInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class LineFormat implements FormatInterface
{
    /**
     * @var string
     */
    protected $format = '<options=bold,underscore>%s</> <alertlevel>%s</> request=<request>%s</> component=<component>%s</>';

    /**
     * @inheritDoc
     */
    public function formatLine(array $matches, string $filename = null): string
    {
        $out =  sprintf($this->getFormat(), $matches['tstamp'], $matches['level'], $matches['request'], $matches['component']);
        return empty($filename) ? $out : $filename . ': ' . $out;
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @inheritDoc
     */
    public function configOutput(OutputInterface $output): void
    {
        try {
            $output->getFormatter()->getStyle('component');
        } catch (InvalidArgumentException $e) {
            $output->getFormatter()->setStyle('component', new OutputFormatterStyle('blue', 'white'));
        }
        try {
            $output->getFormatter()->getStyle('request');
        } catch (InvalidArgumentException $e) {
            $output->getFormatter()->setStyle('request', new OutputFormatterStyle('black', 'white'));
        }
        try {
            $output->getFormatter()->getStyle('alertlevel');
        } catch (InvalidArgumentException $e) {
            $output->getFormatter()->setStyle('alertlevel', new OutputFormatterStyle('white', 'red'));
        }
    }
}
