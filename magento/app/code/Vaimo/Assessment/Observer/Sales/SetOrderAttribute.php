<?php

namespace Vaimo\Assessment\Observer\Sales;

use Magento\Framework\Event\Observer;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface
{
    protected $logger;
    protected $helperData;
    protected $mappingFactory;

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                \Vaimo\Assessment\Helper\Data $helperData,
                                \Vaimo\Assessment\Model\ResourceModel\AccountManagerMapping\CollectionFactory $db)
    {
        $this->logger = $logger;
        $this->helperData = $helperData;
        $this->mappingFactory = $db;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $threshold = $this->helperData->getGeneralConfig('threshold_value');

        $order= $observer->getData('order');
        if ($order->getBaseGrandTotal() > $threshold) {
            $collection = $this->mappingFactory->create();
            $orderRegion = $order->getBillingAddress()->getData('region');

            foreach($collection as $item) {
                //$this->logger->debug($orderRegion .' : '. $item->getProvince());
                if ($orderRegion == $item->getProvince()) {
                    $order->setAccountManager($item->getAccountManager());
                    $order->save();
                    break;
                }
            }
        }

    }
}
