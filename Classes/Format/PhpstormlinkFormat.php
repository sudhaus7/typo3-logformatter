<?php

namespace Sudhaus7\Logformatter\Format;

class PhpstormlinkFormat extends FilelinkFormat {

	/**
	 * @inheritDoc
	 */
	public function getFormat(): string {
		return '%s <href=phpstorm://open?file=%2$s&line=%3$s>%2$s</>(%3$s): %4$s';
	}

}
