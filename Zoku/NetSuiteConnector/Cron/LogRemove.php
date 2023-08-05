<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Cron;

use Zoku\NetSuiteConnector\Model\MessageQueue\Log\Remove;
use Zoku\NetSuiteConnector\Logger\Logger;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\MessageQueue\PublisherInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Magento\Framework\Shell;

/**
 * Remove logs generated
 */
class LogRemove
{
    /**
     * @var Data
     */
    private $jsonHelper;

    /**
     * @var PublisherInterface
     */
    private $publisher;

     /**
      * @var PhpExecutableFinder
      */
    private $phpExecutableFinder;

     /**
      * @var Logger
      */
    private $logger;

    /**
     * Shell command line wrapper for executing command in background
     *
     * @var Shell
     */
    private $shellBackground;

    /**
     * @param Data $jsonHelper
     * @param PublisherInterface $publisher
     * @param PhpExecutableFinder $phpExecutableFinder
     * @param Logger $logger
     * @param Shell $shellBackground
     */
    public function __construct(
        Data $jsonHelper,
        PublisherInterface $publisher,
        PhpExecutableFinder $phpExecutableFinder,
        Logger $logger,
        Shell $shellBackground
    ) {
        $this->publisher = $publisher;
        $this->jsonHelper = $jsonHelper;
        $this->phpExecutableFinder = $phpExecutableFinder;
        $this->logger = $logger;
        $this->shellBackground = $shellBackground;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $details[] = [
            "any_informatic_index" => "value",
        ];

        $this->publisher->publish(
            Remove::TOPIC_NAME,
            $this->jsonHelper->jsonEncode($details)
        );
        $logger = $this->logger;
        $logger->info('All entities remove log initiated');
        $php = $this->phpExecutableFinder->find() ?: 'php';
        $arguments[] = '--max-messages=1 &';
        $command = $php . ' ' . BP . '/bin/magento queue:consumers:start logRemoveConsumer';
        $this->shellBackground->execute($command, $arguments);
    }
}
