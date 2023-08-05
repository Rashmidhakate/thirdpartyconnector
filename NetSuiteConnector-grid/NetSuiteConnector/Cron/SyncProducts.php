<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Cron;

use Zoku\NetSuiteConnector\Model\MessageQueue;
use Symfony\Component\Process\PhpExecutableFinder;
use Magento\Framework\Shell;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Json\Helper\Data;

/**
 * Add/Update Products in magento
 */
class SyncProducts
{
    /**
     * Phpexecutable defined
     *
     * @var \Symfony\Component\Process\PhpExecutableFinder
     */
    protected $phpExecutableFinder;

    /**
     * Shell command defined
     *
     * @var \Magento\Framework\Shell
     */
    protected $shellBackground;

    /**
     * Consumer publisher defined
     *
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    protected $publisher;

    /**
     * Helper json defined
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * Constructor defined
     *
     * @param PhpExecutableFinder $phpExecutableFinder
     * @param Shell               $shellBackground
     * @param PublisherInterface  $publisher
     * @param Data                $jsonHelper
     */
    public function __construct(
        PhpExecutableFinder $phpExecutableFinder,
        Shell $shellBackground,
        PublisherInterface $publisher,
        Data $jsonHelper
    ) {
        $this->phpExecutableFinder  = $phpExecutableFinder;
        $this->shellBackground      = $shellBackground;
        $this->publisher            = $publisher;
        $this->jsonHelper           = $jsonHelper;
    }

    /**
     * Cron execution
     */
    public function execute()
    {
        $details[] = [
            "any_informatic_index" => "value",
        ];

        $this->publisher->publish(
            \Zoku\NetSuiteConnector\Model\MessageQueue\SyncProduct::TOPIC_NAME,
            $this->jsonHelper->jsonEncode($details)
        );

        $php = $this->phpExecutableFinder->find() ?: 'php';
        $arguments[] = '--max-messages=1 &';
        $command = $php . ' ' . BP . '/bin/magento queue:consumers:start syncProductConsumer';
        $this->shellBackground->execute($command, $arguments);
    }
}
