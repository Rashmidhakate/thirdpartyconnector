<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zoku\NetSuiteConnector\Model\Service;

use Zoku\NetSuiteConnector\Service\Service;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Catalog\Model\ProductFactory;

/**
 * Save the prodcuts synced
 */
class SyncProductSave
{

    /**
     * Service class defined
     *
     * @var \Zoku\NetSuiteConnector\Service\Service
     */
    protected $service;

    /**
     * Product Repository defined
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepo;

    /**
     * Serializer Interface defined
     *
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    /**
     * Prodcut Factory defined
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * Constructor defined
     *
     * @param Service                    $service
     * @param ProductRepositoryInterface $productRepo
     * @param SerializerInterface        $serializer
     * @param ProductFactory             $productFactory
     */
    public function __construct(
        Service $service,
        ProductRepositoryInterface $productRepo,
        SerializerInterface $serializer,
        ProductFactory $productFactory
    ) {
        $this->service          = $service;
        $this->productRepo      = $productRepo;
        $this->serializer       = $serializer;
        $this->productFactory   = $productFactory;
    }

    /**
     * Add/update the products
     *
     * @param  string $data
     */
    public function saveProduct($data)
    {
        $data = $this->service->getProductList();
        if (!empty($data)) {
            if (isset($data['body']['list'])) {
                $unserializeData = $this->serializer->unserialize($data['body']['list']);
                if (!empty($unserializeData)) {
                    foreach ($unserializeData as $value) {
                        $sku = $value['sku'];
                        $product = $this->productRepo->get($sku);
                        if ($product->getId() != '') {
                            $product->setName('updated name');
                            $product->save();
                        } else {
                            $product = $this->productFactory->create();     /** new product */
                            $product->setSku('sku123');
                            $product->setName('Sample Sku created');
                            $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
                            $product->setVisibility(4);
                            $product->setPrice(1);
                            $product->setAttributeSetId(4);
                            $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
                            $this->productRepo->save($product);
                        }
                    }
                }
            }
        }
    }
}
