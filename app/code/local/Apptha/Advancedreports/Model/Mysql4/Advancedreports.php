<?php

class Apptha_Advancedreports_Model_Mysql4_Advancedreports extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the advancedreports_id refers to the key field in your database table.
        $this->_init('advancedreports/advancedreports', 'advancedreports_id');
    }
}