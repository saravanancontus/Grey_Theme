<?php

class Apptha_Adddeals_Model_Mysql4_Adddeals extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the adddeals_id refers to the key field in your database table.
        $this->_init('adddeals/adddeals', 'adddeals_id');
    }
}