<?php

namespace Sudhaus7\Logformatter\Pattern;

class StacktracePattern implements PatternInterface {

	/**
	 * @inheritDoc
	 */
	public function getMatches( string $line ): array {
		$m = [];
		preg_match( $this->getPattern(), $line, $m );
		return $m;
	}

	/**
	 * @inheritDoc
	 */
	public function getPattern(): string {
		return '/(?P<index>#\d+)\s+(?P<file>.+)\((?P<linenumber>\d+)\):\s+(?P<msg>.+)/';
	}
}
