<?php

/*
 * This file is part of the TYPO3 project.
 *
 * @author Frank Berger <fberger@sudhaus7.de>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Sudhaus7\Logformatter\Log\Processor;

use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\AbstractProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UrlProcessor extends AbstractProcessor
{
    /**
     * @param LogRecord $logRecord
     *
     * @return LogRecord
     */
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        $logRecord->addData(['request-url'=> GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')]);
        return $logRecord;
    }
}
