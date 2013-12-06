<?php

class Apptha_Advancedreports_Model_Advancedreports extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('advancedreports/advancedreports');
    }
}