<?php

namespace Sudhaus7\Logformatter\Pattern;

use Sudhaus7\Logformatter\Pattern\PatternInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Typo3LogPattern implements PatternInterface {

	/**
	 * @var string
	 */
	private $pattern = '/(?P<tstamp>\w+, \d+ \w+ \d{4} \d{2}:\d{2}:\d{2}\s+\+\d{4})\s+\[(?P<level>\w+)\]\s+request="(?P<request>.+)"\s+component="(?P<component>.+)":\s+(?P<msg>.+)\s+-\s+(?P<json>\{.*\})/';

	/**
	 * @inheritDoc
	 */
	public function getMatches(string $line): array
	{
		$matches = [];
		preg_match( $this->getPattern(), $line, $matches );
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
