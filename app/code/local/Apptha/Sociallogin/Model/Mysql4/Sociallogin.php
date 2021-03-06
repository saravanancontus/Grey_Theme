<?php

/**
 * @name         :  Apptha One Step Checkout
 * @version      :  1.0
 * @since        :  Magento 1.5
 * @author       :  Prabhu Mano
 * @copyright    :  Copyright (C) 2011 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  July 26 2012
 *
 * */
?>
<?php

class Apptha_Sociallogin_Model_Mysql4_Sociallogin extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the sociallogin_id refers to the key field in your database table.
        $this->_init('sociallogin/sociallogin', 'sociallogin_id');
    }
}