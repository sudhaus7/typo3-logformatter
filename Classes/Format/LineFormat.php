<?php

namespace Sudhaus7\Logformatter\Format;

use InvalidArgumentException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class LineFormat implements FormatInterface {

	/**
	 * @var string
	 */
	protected $format = '<options=bold,underscore>%s</> <alertlevel>%s</> request=<info>%s</> component=<component>%s</>';

	/**
	 * @inheritDoc
	 */
	public function formatLine(array $matches, string $filename = null) : string {
		$out =  sprintf( $this->getFormat(), $matches['tstamp'], $matches['level'], $matches['request'], $matches['component'] );
		return empty($filename) ? $out : $filename.': '.$out;
	}

	/**
	 * @inheritDoc
	 */
	public function getFormat(): string {
		return $this->format;
	}

	/**
	 * @inheritDoc
	 */
	public function configOutput( OutputInterface $output ): void {
		try {
			$output->getFormatter()->getStyle( 'component' );
		} catch ( InvalidArgumentException $e) {
			$output->getFormatter()->setStyle('component', new OutputFormatterStyle('blue','white'));
		}
		try {
			$output->getFormatter()->getStyle( 'alertlevel' );
		} catch ( InvalidArgumentException $e) {
			$output->getFormatter()->setStyle('alertlevel', new OutputFormatterStyle('white','red'));
		}
	}
}
