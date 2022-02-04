<?php

namespace Vaimo\Assessment\Model\ResourceModel\AccountManagerMapping;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vaimo\Assessment\Model\AccountManagerMapping', 'Vaimo\Assessment\Model\ResourceModel\AccountManagerMapping');
    }
}
