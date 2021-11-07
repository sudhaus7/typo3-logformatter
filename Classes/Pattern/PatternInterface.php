<?php

namespace Sudhaus7\Logformatter\Pattern;

use Symfony\Component\Console\Output\OutputInterface;

interface PatternInterface {

	/**
	 * Returns the preg pattern for this class
	 *
	 * @return string
	 */
	public function getPattern() : string;

	/**
	 * Returns the matches
	 * @return string[]
	 */
	public function getMatches(string $line) : array;

}

