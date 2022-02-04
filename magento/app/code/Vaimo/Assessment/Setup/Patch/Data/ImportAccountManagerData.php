<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vaimo\Assessment\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class ImportAccountManagerData implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    protected $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Psr\Log\LoggerInterface $logger
    ) {
        /**
         * If before, we pass $setup as argument in install/upgrade function, from now we start
         * inject it with DI. If you want to use setup, you can inject it, with the same way as here
         */
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $file = fopen('accountmangermapping.csv', 'r', '"'); // set path to the CSV file
        if ($file !== false) {

            require 'C:/xampp/htdocs/magento/app/bootstrap.php';
            $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();

            /*$state = $objectManager->get('Magento\Framework\App\State');
            $state->setAreaCode('adminhtml');*/

            $header = fgetcsv($file); // get data headers and skip 1st row

            while ( $row = fgetcsv($file, 100, ",") ) {

                $data_count = count($row);
                if ($data_count < 1) {
                    continue;
                }

                // used for setting the new product data
                $record = $objectManager->create('Vaimo\Assessment\Model\AccountManagerMapping');
                $data = array_combine($header, $row);

                $province = $data['province'];
                $account_manager = $data['account_manager'];

                try {
                    $record->setProvince($province)
                        ->setAccountManager($account_manager)
                        ->save();

                } catch (\Exception $e) {
                    $this->logger->debug('Error importing province'.$province.'. '.$e->getMessage());
                    continue;
                }

                unset($record);
            }
            fclose($file);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->moduleDataSetup->getConnection()->truncateTable("account_manager_mapping");
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
