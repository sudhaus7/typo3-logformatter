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

namespace Sudhaus7\Logformatter\MiddleWares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LogrequesturlMiddleWare implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('logformatter');
        if (isset($config['logrequesturl']) && (bool)$config['logrequesturl']) {
            $this->setLogger(GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__));
            $this->logger->info((string)$request->getUri());
        }
        return $handler->handle($request);
    }
}
