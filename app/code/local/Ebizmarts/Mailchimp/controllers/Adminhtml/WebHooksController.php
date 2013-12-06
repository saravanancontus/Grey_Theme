<?php

class Ebizmarts_Mailchimp_Adminhtml_WebHooksController extends Mage_Adminhtml_Controller_Action{

	public function indexAction() {

    	$this->loadLayout()
        	 ->_addContent($this->getLayout()->createBlock('mailchimp/adminhtml_webHooks'))
          	 ->renderLayout();
	}

	public function newAction(){

		$mod = 0;

     	foreach($this->getRequest()->getPost() as $list){
     		$items = explode('&',$list);

     		if(is_array($items) && count($items) > 1){
     			Mage::getSingleton('mailchimp/webHooks')->mainWebHooksAction($items);
     			$mod++;
     		}
    	}

		if(count($mod)){
			if($mod == 1){
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mailchimp')->__('Total of %d list was updated.', $mod));
			}else{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mailchimp')->__('Total of %d list(s) were updated.', $mod));
			}
		}
	}

}