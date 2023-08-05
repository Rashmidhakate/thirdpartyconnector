<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Controller\Adminhtml\Product;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Zoku\NetSuiteConnector\Model\ResourceModel\ProductGrid\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\File\Csv;

/**
 * Class MassDelete
 */
class MassDownload extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Zoku_NetSuiteConnector::product_view';

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
            'log_id' => __('Entity ID'),
            'sku' => __('SKU'),
            'price' => __('Price'),
            'qty' => __('Qty'),
            'product_netsuite_id' => __('Product NetSuite ID'),
            'request_payload' => __('Request Payload'),
            'response_payload' => __('Response Payload'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];

        $fileName = 'Zoku_NetSuiteConnector_Product_Log.csv';
        $filePath =  $this->directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
        foreach ($collection as $product) {
            $content[] = [
                $product->getLogId(),
                $product->getSku(),
                $product->getPrice(),
                $product->getQty(),
                $product->getProductNetsuiteId(),
                $product->getRequestPayload(),
                $product->getResponsePayload(),
                $product->getCreatedAt(),
                $product->getUpdatedAt()
            ];
        }

        $this->csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
        $this->fileFactory->create(
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
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been downloaded.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
