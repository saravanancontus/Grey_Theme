<?php

class Ebizmarts_Mailchimp_Model_Mailchimp extends Ebizmarts_Mailchimp_Model_MCAPI {

	const ACTION_SUBSCRIBE     	 = 'subscribe';
	const ACTION_SILENTSUBSCRIBE = 'silentSubscribe';
	const ACTION_RESUBSCRIBE     = 'resubscribe';
	const ACTION_UPDATE     	 = 'update';
	const ACTION_SILENTUPDATE	 = 'silentUpdate';
	const ACTION_UNSUBSCRIBE     = 'unsubscribe';

	private function setSubsParams(){

		$helper = Mage::helper('mailchimp');

		$this->setEmailType((string)$helper->getSubscribeConfig('email_type',$this->getStore()));
		// if user subscribes own login email - confirmation is not needed
		$this->setDoubleOptin(($this->getCustomer()->getEntityId())? (bool)0 : (bool)$helper->getSubscribeConfig('double_optin',$this->getStore()));
		$this->setUpdateExisting((bool)$helper->getSubscribeConfig('update_existing',$this->getStore()));
		if($this->getCustomer()->getAction() == self::ACTION_UPDATE){
			$this->setReplaceInterests((bool)0);
		}elseif($this->getCustomer()->getAction() == self::ACTION_UNSUBSCRIBE){
			$this->setReplaceInterests((bool)1);
		}else{
			$this->setReplaceInterests((bool)$helper->getSubscribeConfig('replace_interests',$this->getStore()));
		}
		$this->setSendWelcome(($this->getCustomer()->getisSilentUpdate())? (bool)0 : (bool)$helper->getSubscribeConfig('send_welcome',$this->getStore()));

		return $this;
	}

	private function setUnSubsParams(){

		$helper = Mage::helper('mailchimp');

		$this->setDeleteMember((bool)$helper->getUnSubscribeConfig('delete_member',$this->getStore()));
		$this->setSendGoodbye(($this->getCustomer()->getisSilentUpdate())? (bool)0 :(bool)$helper->getUnSubscribeConfig('send_goodbye',$this->getStore()));
		$this->setSendNotify(($this->getCustomer()->getisSilentUpdate())? (bool)0 : (bool)$helper->getUnSubscribeConfig('send_notify',$this->getStore()));

		return $this;
	}

	private function subscribeCustomer(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		$this->setSubsParams();
		$merge_vars = Mage::helper('mailchimp')->getMergeVars($this->getCustomer(),false);
                
                $customer = $this->getCustomer();
                $subscriber = Mage::getSingleton('newsletter/subscriber')->loadByEmail($customer->getEmail());
                $merge_vars['CITY'] = $subscriber->getSubscriberCity();

		try {
			$retval = $this->listSubscribe($this->getListId(),
										   $this->getEmail(),
										   $merge_vars,
										   $this->getEmailType(),
										   $this->getDoubleOptin(),
										   $this->getUpdateExisting(),
										   $this->getReplaceInterests(),
										   $this->getSendWelcome());
			$this->registerSubscription();
		   	return $retval;
		}catch (Exception $e) {
			Mage::helper('mailchimp')->addException($e);
        }

		return $this;
	}

	private function unSubscribeCustomer(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		$this->setUnSubsParams();
		try {
			$retval = $this->listUnsubscribe($this->getListId(),
											 $this->getCustomer()->getIdentifier(),
											 $this->getDeleteMember(),
											 $this->getSendGoodbye(),
											 $this->getSendNotify());
			$this->registerSubscription();
		   	return $retval;
		}catch (Exception $e) {
			Mage::helper('mailchimp')->addException($e);
        }

		return $this;
	}

	private function updateCustomer(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		$this->setSubsParams();
		$merge_vars = Mage::helper('mailchimp')->getMergeVars($this->getCustomer(),true);

                $customer = $this->getCustomer();
                $subscriber = Mage::getSingleton('newsletter/subscriber')->loadByEmail($customer->getEmail());
                $merge_vars['CITY'] = $subscriber->getSubscriberCity();

		try {
			$retval = $this->listUpdateMember($this->getListId(),
										      $this->getCustomer()->getIdentifier(),
										      $merge_vars,
										      $this->getEmailType(),
										      $this->getReplaceInterests());
			$this->registerSubscription();
			return $retval;
		}catch (Exception $e) {
			Mage::helper('mailchimp')->addException($e);
        }

		return $this;
	}

	public function getListMemberInfo(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		try {
			$retval = $this->listMemberInfo($this->getListId(),$this->getEmail());
			if($retval['success'] > 0){
				return $retval['data'][0];
			}
		}catch (Exception $e) {
			Mage::helper('mailchimp')->addException($e);
        }

		return false;
	}

	private function getListInterestGroupings(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		try{
			$retval = $this->listInterestGroupings($this->getListId());
			return $retval;
		}catch (Exception $e) {
			Mage::helper('mailchimp')->addException($e);
        }

		return $this;
	}

	public function getLists(){

		$apikey = Mage::helper('mailchimp')->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);

		try{
			$retval = $this->lists();
			return $retval;
		}catch (Exception $e) {
        	Mage::helper('mailchimp')->addException($e);
        }

        return $this;
	}

	public function mainAction(){

		if($this->getCustomer()->getAction() == self::ACTION_SUBSCRIBE ||
			$this->getCustomer()->getAction() == self::ACTION_RESUBSCRIBE ||
			$this->getCustomer()->getAction() == self::ACTION_SILENTSUBSCRIBE){
			$this->subscribeCustomer();
		}elseif($this->getCustomer()->getAction() == self::ACTION_UPDATE){
			$this->updateCustomer();
		}elseif($this->getCustomer()->getAction() == self::ACTION_SILENTUPDATE){
			$response = $this->subscribeCustomer();
			if($response){
				$response = $this->updateCustomer();
				if($response){
					$this->unSubscribeCustomer();
				}
			}
		}elseif($this->getCustomer()->getAction() == self::ACTION_UNSUBSCRIBE){
			$response = $this->updateCustomer();
			if($response){
				$this->unSubscribeCustomer();
			}
		}
	}

	public function getGroupsByListId(){

		$listgroups = $this->getListInterestGroupings();

		if($listgroups){
			$registered = '';

			if($this->getCustomerSession()->getId()){
				$subscriber = Mage::getSingleton('customer/customer')->load($this->getCustomerSession()->getId());
				$subscriber->setCustomerId($subscriber->getEntityId())
						   ->setSubscriberEmail($subscriber->getEmail())
						   ->setListId($this->getListId());
				$registered = Mage::helper('mailchimp')->isMailchimpSubscribed($subscriber)->getData();
			}


			if(!empty($registered)){
				$group = array();

				$this->setEmail($subscriber->getEmail());
				$member = $this->getListMemberInfo();

				if(isset($member['id'])){
					foreach($member['merges']['GROUPINGS'] as $val){
						$group = array_merge($group, explode(', ',$val['groups']));
			        }
					if(count($group)){
						foreach($listgroups as $ky=>$item){
							foreach($item['groups'] as $k=>$val){
								if(in_array($val['name'],$group)){
									$listgroups[$ky]['groups'][$k]['checked'] = true;
								}
							}
						}
					}
				}
			}
			return $listgroups;
		}
        return '';
	}

	private function registerSubscription(){

		$member = $this->getListMemberInfo();
		$memberId = (isset($member['id']))? $member['id']: $this->getCustomer()->getEmail();
		$this->getCustomer()->setMemberId($memberId);
		$this->getCustomer()->setIdentifier($memberId);
		if($this->getCustomer()->getRegistered()) $this->getCustomer()->setMailchimpproId($this->getCustomer()->getRegistered()->getMailchimpproId());
		Mage::getSingleton('mailchimp/subscripter')->registerInfo($this->getCustomer());

		return true;
	}

}
