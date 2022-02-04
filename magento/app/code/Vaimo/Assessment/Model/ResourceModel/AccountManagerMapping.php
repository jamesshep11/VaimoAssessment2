<?php


namespace Vaimo\Assessment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AccountManagerMapping extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('account_manager_mapping', 'id');
    }
}
