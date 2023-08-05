<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Controller\Adminhtml\Order;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Zoku\NetSuiteConnector\Model\ResourceModel\OrderGrid\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\File\Csv;
use Magento\Backend\App\Action as Action;

/**
 * Class MassDownload Defined
 */
class MassDownload extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Zoku_NetSuiteConnector::order_view';

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var Csv
     */
    protected $csvProcessor;

        /**
         * @var DirectoryList
         */
    protected $directoryList;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param FileFactory $fileFactory
     * @param Csv $csvProcessor
     * @param DirectoryList $directoryList
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        FileFactory $fileFactory,
        Csv $csvProcessor,
        DirectoryList $directoryList
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        /** header name */
        $content[] = [
            'log_id' => __('Log ID'),
            'order_email' => __('Email'),
            'increment_id' => __('Increment ID'),
            'order_netsuite_id' => __('Order NetSuite ID'),
            'request_payload' => __('Request Payload'),
            'response_payload' => __('Response Payload'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];

        $fileName = 'Zoku_NetSuiteConnector_Order_Log.csv';
        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
        foreach ($collection as $order) {
            $content[] = [
                $order->getLogId(),
                $order->getEmail(),
                $order->getIncrementId(),
                $order->getOrderNetsuiteId(),
                $order->getRequestPayload(),
                $order->getResponsePayload(),
                $order->getCreatedAt(),
                $order->getUpdatedAt()
            ];
        }

        $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
        return $this->fileFactory->create(
            $fileName,
            [
                'type'  => "filename",
                'value' => $filePath,
                'rm'    => false,
            ],
            DirectoryList::MEDIA,
            'text/csv',
            null
        );
    }
}
