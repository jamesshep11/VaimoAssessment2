<?php

namespace Vaimo\Assessment\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

class AccountManagerMapping extends AbstractModel
{
    protected $_province;
    protected $_account_manager;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Vaimo\Assessment\Model\ResourceModel\AccountManagerMapping::class);
    }

}
