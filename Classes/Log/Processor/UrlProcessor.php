<?php

/*
 * This file is part of the TYPO3 project.
 *
 * (c) 2019-2022 Frank Berger <fberger@sudhaus7.de>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Sudhaus7\Logformatter\Log\Processor;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Log\LogRecord;
use TYPO3\CMS\Core\Log\Processor\AbstractProcessor;

class UrlProcessor extends AbstractProcessor
{
    /**
     * @param LogRecord $logRecord
     *
     * @return LogRecord
     */
    public function processLogRecord(LogRecord $logRecord): LogRecord
    {
        $logRecord->addData(['url'=>(string)$this->getRequest()->getUri()]);
        return $logRecord;
    }

    /**
     * @return ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
