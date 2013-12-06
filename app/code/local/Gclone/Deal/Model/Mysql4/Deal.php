<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */

class Gclone_Deal_Model_Mysql4_Deal extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the deal_id refers to the key field in your database table.
        $this->_init('deal/deal', 'deal_id');
    }
}