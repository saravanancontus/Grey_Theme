<?php

class Contus_Videoupload_Model_Mysql4_Videoupload_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('videoupload/videoupload');
    }
}