<?php

namespace Zoku\NetSuiteConnector\Observer\Adminhtml\Customer;

use Magento\Framework\Exception\LocalizedException;

/**
 * Event for admin customer update
 */
class AdminCustomerUpdate implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var Zoku\NetSuiteConnector\Service\Service
     */
    private $service;

    /**
     * @var Magento\Customer\Api\AddressRepositoryInterface
     */
    private $addressInterface;

    /**
     * @var Magento\Customer\Model\Customer
     */
    private $customer;

    /**
     * @var Magento\Customer\Model\ResourceModel\CustomerFactory
     */
    private $customerFactory;

    /**
     * Constructor defined
     *
     * @param \Zoku\NetSuiteConnector\Service\Service                  $service
     * @param \Magento\Customer\Api\AddressRepositoryInterface         $addressInterface
     * @param \Magento\Customer\Model\Customer                         $customer
     * @param \Magento\Customer\Model\ResourceModel\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Zoku\NetSuiteConnector\Service\Service $service,
        \Magento\Customer\Api\AddressRepositoryInterface $addressInterface,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory
    ) {
        $this->service     = $service;
        $this->addressInterface = $addressInterface;
        $this->customer     = $customer;
        $this->customerFactory = $customerFactory;
    }

    /**
     * Observer called when customer is updated from admin
     *
     * @param  \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $customer = $observer->getCustomer();

        try {
            if (!empty($customer)) {
                $id = $customer->getId();
                $email = $customer->getEmail();
                $firstname = $customer->getFirstname();
                $lastname = $customer->getLastname();

                $telephone = '';
                $billingAddId = $customer->getDefaultBilling();
                if (!empty($billingAddId)) {
                    $billingAddress = $this->addressInterface->getById($billingAddId);
                    $telephone = $billingAddress->getTelephone();
                }
                
                $data = [
                    'id' => $id,
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'telephone' => $telephone
                ];

                $response = $this->service->upsertCustomer($data);

                if (!empty($response)) {
                    $customerData = $this->customer->load($id);
                    $customerModel = $customerData->getDataModel();
                    $customerModel->setCustomAttribute('zoku_netsuite_customer_id', $response);
                    $customerData->updateData($customerModel);
                    $customerResource = $this->customerFactory->create();
                    $customerResource->saveAttribute($customerData, 'zoku_netsuite_customer_id');
                }
            }
        } catch (LocalizedException $e) {
            throw new LocalizedException($e->getMessage());
        }
    }
}
