<?php

class Apptha_Advancedreports_Model_Mysql4_Advancedreports_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancedreports/advancedreports');
    }
}