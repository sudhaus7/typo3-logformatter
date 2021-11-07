<?php

namespace Sudhaus7\Logformatter\Format;

use Symfony\Component\Console\Output\OutputInterface;

class LineFormat implements FormatInterface {

	/**
	 * @var string
	 */
	protected $format = '<options=bold,underscore>%s</> <fg=bright-white;bg=red>%s</> request=<info>%s</> component=<fg=bright-blue>%s</>';

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

	}
}
