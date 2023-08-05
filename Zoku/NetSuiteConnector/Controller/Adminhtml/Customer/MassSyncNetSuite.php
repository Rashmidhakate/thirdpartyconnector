<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Controller\Adminhtml\Customer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

/**
 * Mass action for net suite
 */
class MassSyncNetSuite extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Constructor defined
     *
     * @param Context                     $context
     * @param Filter                      $filter
     * @param CustomerRepositoryInterface $customerRepository
     * @param CollectionFactory           $customerFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CustomerRepositoryInterface $customerRepository,
        CollectionFactory $customerFactory
    ) {
        $this->filter = $filter;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * Main method
     */
    public function execute()
    {
        $reqParam = $this->getRequest()->getParams();

        $collection = $this->filter->getCollection($this->customerFactory->create());
        foreach ($collection->getAllIds() as $customerId) {
            $customerObj = $this->customerRepository->getById($customerId);
            $this->customerRepository->save($customerObj);
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('customer/index/index');
    }
}
