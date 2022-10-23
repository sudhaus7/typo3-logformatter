<?php

namespace Sudhaus7\Logformatter\Factory;

use Sudhaus7\Logformatter\Format\FilelinkFormat;
use Sudhaus7\Logformatter\Format\PhpstormlinkFormat;
use Sudhaus7\Logformatter\Interfaces\FormatInterface;
use Sudhaus7\Logformatter\Traits\TestclassTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilelinkFormatFactory {

	use TestclassTrait;

	public function get() : FormatInterface
	{
		$filelinkFormat = null;
		if ($this->checkConfig('filelinkFormat')) {
			/** @var string $className */
			$className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['logformatter']['filelinkFormat'];
			if($this->checkIfClassExistsAndImplementsInterface( $className)) {
				/** @var \Sudhaus7\Logformatter\Interfaces\FormatInterface $filelinkFormat */
				$filelinkFormat = GeneralUtility::makeInstance( $className );
			}
		}

		if (getenv('LOGFORMATTER_FILELINKFORMATTER')) {
			$className = str_replace('.','\\',getenv('LOGFORMATTER_FILELINKFORMATTER'));
			if($this->checkIfClassExistsAndImplementsInterface( $className)) {
				/** @var \Sudhaus7\Logformatter\Interfaces\FormatInterface $filelinkFormat */
				$filelinkFormat = GeneralUtility::makeInstance( $className );
			}
		}

		if ($filelinkFormat === null) {
			$filelinkFormat = GeneralUtility::makeInstance( FilelinkFormat::class);
		}
		return $filelinkFormat;
	}



}
