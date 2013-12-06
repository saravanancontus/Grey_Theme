<?php

class Apptha_Adddeals_Model_Mysql4_Adddeals_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('adddeals/adddeals');
    }
}