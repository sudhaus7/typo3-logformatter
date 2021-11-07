<?php

namespace Sudhaus7\Logformatter\Format;

use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

class FilelinkFormat implements FormatInterface {


	/**
	 * @inheritDoc
	 */
	public function formatLine( array $matches, string $filename = null ): string {
		return sprintf( $this->getFormat(),$matches['index'],$matches['file'],$matches['linenumber'],$matches['msg']);
	}

	/**
	 * @inheritDoc
	 */
	public function getFormat(): string {
		return '%s <href=file://%2$s>%2$s</>(%3$s): %4$s';
	}

	/**
	 * @inheritDoc
	 */
	public function configOutput( OutputInterface $output ): void {
	}
}
