<?php

class Contus_Videoupload_Model_Mysql4_Videoupload extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the videoupload_id refers to the key field in your database table.
        $this->_init('videoupload/videoupload', 'videoupload_id');
    }
}