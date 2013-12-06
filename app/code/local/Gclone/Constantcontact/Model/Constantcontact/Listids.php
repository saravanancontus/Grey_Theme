<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 * The CONTUS GCLONE License is available at this URL:
 * http://www.groupclone.net/GCLONE-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */

class Gclone_Constantcontact_Model_Constantcontact_Listids
{
    protected $_options;
    protected $_lists = array();

	protected function getConstantcontact()
	{
		return Mage::getModel('constantcontact/constantcontact');
	}

    public function toOptionArray($isMultiselect=false)
    { 
        require_once 'cc_class.php';

        //when the object is getting initialized, the login string must be created as API_KEY%LOGIN:PASSWORD
        $login = Mage::getStoreConfig('constantcontact/general/username'); //Username for your account
        $password = Mage::getStoreConfig('constantcontact/general/password'); //Password for your account
        $apikey = Mage::getStoreConfig('constantcontact/general/apikey'); // API Key for your account.
        $authstr = $apikey . '%' . $login . ':' . $password;
        $apiPath = 'https://api.constantcontact.com/ws/customers/'.$login;

        $ccListOBJ = new CC_List($authstr, $apiPath);
        $allLists = $ccListOBJ->getLists();
        //echo '<pre>'; print_r($allLists); die;
        			
			foreach ($allLists as $k=>$item)
			{
				$this->_lists[] = array('value'=>$item['id'],
										'label'=>$item['title']);
			}

            $this->_options = $this->_lists;
       

        $options = $this->_options;
        if(!$isMultiselect){
            array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
        }
		return $options;
    }
}