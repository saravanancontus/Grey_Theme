<?php

class Ebizmarts_Mailchimp_Adminhtml_SubscribersController extends Mage_Adminhtml_Controller_Action{

	public function indexAction() {

    	$this->loadLayout()
        	 ->_addContent($this->getLayout()->createBlock('mailchimp/adminhtml_subscribers'))
          	 ->renderLayout();
	}

}