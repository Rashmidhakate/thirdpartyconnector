<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer account reward history block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Zoku\NetSuiteConnector\Block\Customer\Reward;

use Magento\Reward\Block\Customer\Reward\History as RewardHistory;
use Zoku\NetSuiteConnector\Service\Service as BaseService;
use Magento\Framework\View\Element\Template\Context as Context;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Reward\Helper\Data as RewardData;
use Magento\Customer\Helper\Session\CurrentCustomer as CurrentCustomer;
use Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory as HistoryFactory;

/**
 * @api
 * @since 100.0.2
 */
class History extends RewardHistory
{
    /**
     * History records collection
     *
     * @var \Magento\Reward\Model\ResourceModel\Reward\History\Collection
     */
    protected $_collection = null;

    /**
     * Reward data
     *
     * @var \Magento\Reward\Helper\Data
     */
    protected $_rewardData = null;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Reward\Model\ResourceModel\Reward\History\CollectionFactory
     */
    protected $_historyFactory;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * Base Service defined
     *
     * @var BaseService
     */
    protected $baseService;

    /**
     * @param Context $context
     * @param PricingHelper $pricingHelper
     * @param RewardData $rewardData
     * @param CurrentCustomer $currentCustomer
     * @param HistoryFactory $historyFactory
     * @param BaseService $baseService
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        PricingHelper $pricingHelper,
        RewardData $rewardData,
        CurrentCustomer $currentCustomer,
        HistoryFactory $historyFactory,
        BaseService $baseService,
        array $data = []
    ) {
        $this->pricingHelper = $pricingHelper;
        $this->_rewardData = $rewardData;
        $this->currentCustomer = $currentCustomer;
        $this->_historyFactory = $historyFactory;
        $this->baseService =  $baseService;
        parent::__construct(
            $context,
            $pricingHelper,
            $rewardData,
            $currentCustomer,
            $historyFactory,
            $data
        );
    }

    /**
     * Whether the history is supposed to be rendered
     *
     * @return bool
     */
    public function checkEnabled()
    {
        $flag = true;
        if (!$this->baseService->getLoyaltyPointsConfig() || !$this->baseService->getModuleEnable()){
            $flag = false;
        }
        return $flag;
    }

    /**
     * Fetch current customer id
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->currentCustomer->getCustomerId();
    }
}
