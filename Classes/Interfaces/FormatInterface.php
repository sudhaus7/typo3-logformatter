<?php

namespace Sudhaus7\Logformatter\Interfaces;

use Symfony\Component\Console\Output\OutputInterface;

interface FormatInterface {

	/**
	 * Returns the Symfony COmmand Output compatible Format
	 * @return string
	 */
	public function getFormat() : string;

	/**
	 * Do the actual formatting
	 *
	 * @param string[] $matches matches Produced by a Sudhaus7\Logformatter\Pattern\PatternInterface
	 * @param string|null $filename Optional Filename of the Logfile
	 *
	 * @return string
	 */
	public function formatLine(array $matches, string $filename = null) : string;

	/**
	 * This can be used to add style marker to the Symfony's output object
	 * @param OutputInterface $output
	 */
	public function configOutput(OutputInterface $output) : void;
}
