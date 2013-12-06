<?php
class Ebizmarts_Mailchimp_Helper_Data extends Mage_Core_Helper_Abstract{

	const BULK_URL    = '/mailchimp/adminhtml_bulkSync/';

	public function getGeneralConfig($field,$store=null){

		return Mage::getStoreConfig('mailchimp/general/'.$field,$store);
	}

	public function getSubscribeConfig($field,$store=null){

		return Mage::getStoreConfig('mailchimp/subscribe/'.$field,$store);
	}

	public function getUnSubscribeConfig($field,$store=null){

		return Mage::getStoreConfig('mailchimp/unsubscribe/'.$field,$store);
	}

	public function getUserAgent(){

		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array)$modules;

		$aux = (array_key_exists('Enterprise_Enterprise',$modulesArray))? 'EE' : 'CE' ;
		return (string)'Ebizmarts/Mage'.$aux.Mage::getVersion().'/'.$this->getGeneralConfig('version');
	}

	public function getApiKey(){

		$apikey = $this->getGeneralConfig('apikey',Mage::app()->getStore()->getStoreId());

                if($apikey == ''){
			//Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('mailchimp')->__('The Ebizmarts MailChimp API Key field is empty.'));
			return false;
                }
		if($apikey != ''){
                if(substr($apikey, -4) != '-us1' && substr($apikey, -4) != '-us2'){
			//Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mailchimp')->__('Ebizmarts MailChimp General Error, the API Key is not well formed.'));
			return false;
		}
                }
                return $apikey;
	}

	public function mailChimpEnabled($store){

		if((bool)$this->getGeneralConfig('active',$store) == true && $this->getGeneralConfig('apikey',$store)
			&& $this->getGeneralConfig('general',$store)){
			return true;
		}
		return false;
	}

	public function isEcomm360Activated(){

		$store = Mage::app()->getStore()->getStoreId();
		if((bool)$this->getGeneralConfig('active',$store) == true && $this->getGeneralConfig('ecomm360',$store)){
			return true;
		}
		return false;
	}

	public function checkSendSubscribe(){

		$store = Mage::app()->getStore()->getStoreId();
		$current = Mage::helper('core/url')->getCurrentUrl();

		if($this->mailChimpEnabled($store)){
			$return = ((bool)$this->getSubscribeConfig('double_optin',$store)
					|| (bool)$this->getSubscribeConfig('send_welcome',$store)
					|| (bool)strpos($current, self::BULK_URL))? false : true;
		}else{
			$return = true;
		}

		return $return;
	}

	public function checkSendUnsubscribe(){

		$store = Mage::app()->getStore()->getStoreId();
		$current = Mage::helper('core/url')->getCurrentUrl();

		if($this->mailChimpEnabled($store)){
			$return = ((bool)$this->getUnSubscribeConfig('send_notify',$store)
					|| (bool)$this->getUnSubscribeConfig('send_goodbye',$store)
					|| (bool)strpos($current, self::BULK_URL))? false : true;
		}else{
			$return = true;
		}

		return $return;
	}

	public function isSubscribed($email){

		return Mage::getModel('newsletter/subscriber')
					->loadByEmail($email)
					->isSubscribed();
	}

	public function isMailchimpSubscribed($subscriber){

		$currentEmail = ($subscriber->getOldEmail())? $subscriber->getOldEmail() : $subscriber->getSubscriberEmail();

		$col = Mage::getModel('mailchimp/subscripter')->getCollection()
				->addFieldToFilter('store_id',$subscriber->getStoreId())
				->addFieldToFilter('list_id',$subscriber->getListId())
				->addFieldToFilter('current_email',$currentEmail)
				->getLastItem();

		return $col;
	}

	public function getAvailableLists($currentStore){

		if($this->mailChimpEnabled($currentStore)){
			$avlists = explode(',',$this->getGeneralConfig('listid',$currentStore));
			$lists = array();
			foreach($avlists as $list){
				if($list != $this->getGeneralConfig('general',$currentStore)){
					$lists[$list] = $list;
				}
			}

			if(count($lists)){
				$aux = Mage::getSingleton('mailchimp/source_lists')->toOptionArray();
				foreach($lists as $list){
					if(array_key_exists($list,$aux)){
						$lists[$list] = $aux[$list]['label'];
					}
				}
			}
			return $lists;
		}
		return false;
	}

	public function getMergeVars($customer,$flag){

		$merge_vars = array();
		$maps = explode('<',$this->getSubscribeConfig('mapping_fields',Mage::app()->getStore()->getStoreId()));
		foreach($maps as $map){
			if($map){
				$aux = substr(strstr($map,"customer='"),10);
				$customAtt = (string)substr($aux,0,strpos($aux,"'"));
				$aux = substr(strstr($map,"mailchimp='"),11);
				$chimpTag = (string)substr($aux,0,strpos($aux,"'"));
				if($chimpTag && $customAtt){
					if($customAtt == 'address'){
						$address = $customer->getAddress();
						$merge_vars[strtoupper($chimpTag)] = array('addr1'=>$address['street'],
																   'addr2'=>'',
																   'city'=>$address['city'],
																   'state'=>$address['region'],
																   'zip'=>$address['postcode'],
																   'country'=>$address['country_id']);
					}else{
						if($value = (string)$customer->getData(strtolower($customAtt))) $merge_vars[strtoupper($chimpTag)] = $value;
					}
				}
			}
		}
                $subscriber = Mage::getSingleton('newsletter/subscriber')->loadByEmail($customer->getEmail());
		if($flag) $merge_vars['EMAIL'] = $customer->getEmail();
                $merge_vars['CITY'] = $subscriber->getSubscriberCity();
		$groups = $customer->getListGroups();
		$interests = $grpToSend = array();
		if(is_array($groups) && count($groups)){
			$groupings = array();
			$grpName = '';
			$count = 0;
			foreach($groups as $option){
				$parts = explode(']',$option);
				if(substr($parts[0],1) == $customer->getListId() && count($parts) == 5){
					if($count == 0 || $grpName != substr($parts[1],1)){
						$grpName = substr($parts[1],1);
						$currentInterests = $currentInterest = array();
						if($count) $grpToSend[] = end($groupings);
					}
					$count++;
					if(substr($parts[3],1) != '') $interests[] = substr($parts[3],1);
					$currentInterest[] = substr($parts[3],1);
					$currentInterests = implode(", ", $currentInterest);
					$groupings[] = array('id'=>substr($parts[2],1),
									   'name'=>$grpName,
									   'groups'=>$currentInterests);
				    if($count == count($groups)) $grpToSend[] = end($groupings);
				}
			}
		}
		$merge_vars['GROUPINGS'] = $grpToSend;
		$merge_vars['INTERESTS'] = ($interests)? implode(", ", $interests) : '';

		return $merge_vars;
	}

	private function getAllListGroups($params,$currentStore){

		$allGrps = array();

		if($this->getGeneralConfig('intgr',$currentStore)){
			$params = (is_array($params))? $params : $allGrps;

			if(isset($params['group']) || isset($params['allgroups'])){
				$flag = false;
				if(isset($params['group'])){
					foreach($params['group'] as $list=>$group){
						if($group){
							$flag = true;
							$arrayCheck = array();
						}
					}
				}
				$groups = ($flag)? $params['group'] : $params['allgroups'];

				foreach($groups as $list=>$group){
					if(is_array($group)){
						foreach($group as $checkbox){
							if(is_array($checkbox)){
								foreach($checkbox as $item){
									$allGrps[] = '['.$list.']'.str_replace('|','][',$item);
									$arrayCheck[] = $item;
								}
							}else{
								if($checkbox){
									if(strstr($checkbox,']')){
										$allGrps[] = '['.$list.']'.$checkbox;
									}else{
										$allGrps[] = '['.$list.']'.str_replace('|','][',$checkbox).'][]';
									}
								}
							}
						}
					}else{
						if($group && strstr($group,'][')){
							$allGrps[] = '['.$list.']'.str_replace('|','][',$group);
						}else{
							$allGrps[] = '['.$list.']'.str_replace('|','][',$params['allgroups'][$list]).'[]';
						}
					}
				}

				if($flag && isset($params['allgroups'])){
					$missingGrps = explode("]",str_replace("[","",$params['allgroups'][$list]));
					foreach($missingGrps as $fixing){
						if($fixing && !in_array('['.$fixing.'][]',$arrayCheck)){
							$allGrps[] = '['.$list.']['.str_replace('|','][',$fixing).'][]';
						}
					}
				}
			}
		}
		return $allGrps;
	}

	public function preFilter($email,$params){

		$subscriber = Mage::getSingleton('newsletter/subscriber')->loadByEmail($email);
		if(!$subscriber->getData()){
			$customSession = Mage::getSingleton('customer/session')->getCustomer();
			$subscriber = Mage::getSingleton('customer/customer')
							->setWebsiteId($customSession->getWebsiteId())
							->loadByEmail($email);
			if(!$subscriber->getData()){
				$subscriber = $customSession;
				if(!$subscriber->getEmail()){
					$subscriber->setStoreId(Mage::app()->getStore()->getStoreId())
							   ->setCustomerId(0)
							   ->setEmail($email);
				}
			}else{
				$subscriber->setCustomerId($subscriber->getEntityId());
			}
		}
		if(isset($params['oldEmail'])) $subscriber->setOldEmail($params['oldEmail']);

		return $this->mailChimpFilter($subscriber,$params);
	}

	protected function mailChimpFilter($subscriber,$params){

		if($this->mailChimpEnabled($subscriber->getStoreId())){

			if($subscriber->getStoreId() == '0') $subscriber->setStoreId(Mage::app()->getDefaultStoreView()->getStoreId());
			$allGrps = $this->getAllListGroups($params,$subscriber->getStoreId());
			$subscriber->setListGroups($allGrps);

			if($subscriber->getCustomerId()){
				$customer = Mage::getSingleton('customer/customer')->load($subscriber->getCustomerId())->setSoreId($subscriber->getStoreId());

				foreach($customer->getData() as $k=>$v) $subscriber->setData($k,$v);

				$address = ($customer->getDefaultBillingAddress())? $customer->getDefaultBillingAddress() : Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress() ;

				if($address->getStreet()){
					$addressArray = array();
					foreach($address->getData() as $k=>$v){
						$addressArray[$k] = $v;
					}
					$subscriber->setAddress($addressArray);
				}

			}else{
				$billing = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();

				if((string)$billing->getEmail() == (string)$subscriber->getEmail()){
					$address = array();
					foreach($billing->getData() as $k=>$v){
						$subscriber->setData($k,$v);
						$address[$k] = $v;
					}
					$subscriber->setAddress($address);
				}else{
					$subscriber->setFirstname('GUEST')
							   ->setLastname('GUEST');
				}
			}

			if(isset($params['is_general'])){
				$subscriber->setListId($this->getGeneralConfig('general',$subscriber->getStoreId()));
				$this->doTheAction($subscriber);
			}elseif(isset($params['onlyUpdate'])){
				$list = Mage::getSingleton('mailchimp/subscripter')->getListsByCustomer($subscriber);
				$subscriber->setIsOnlyUpdate(true);
				foreach($list as $k=>$v){
					if($v == (bool)0){
						$subscriber->setisSilentUpdate(true);
					}
					$subscriber->setListId($k);
					$this->doTheAction($subscriber);
				}
			}elseif(isset($params['additional'])){
				$subscriber->setIsAdditionalList(true);
				$allLists = $this->getAvailableLists($subscriber->getStoreId());
				$lists = (isset($params['list']))? $params['list']: array($this->getGeneralConfig('general',$subscriber->getStoreId())=>(int)1);
				foreach($allLists as $k=>$v){
					if($k && !array_key_exists($k,$lists)){
						$subscriber->setToUnsubscribe(true);
					}
					$subscriber->setListId($k);
					$this->doTheAction($subscriber);
				}
			}
		}
		return true;
	}

	private function doTheAction($subscriber){

		$mainModel = Mage::getModel('mailchimp/mailchimp');
		$mainModel->setStore($subscriber->getStoreId());
		if($subscriber->getOldEmail()){
			$mainModel->setEmail($subscriber->getOldEmail());
		}else{
			$mainModel->setEmail(($subscriber->getEmail())? $subscriber->getEmail() : $subscriber->getSubscriberEmail());
		}
		$mainModel->setListId($subscriber->getListId());
	    $member = $mainModel->getListMemberInfo();

		$isSubscribed = ($subscriber->getIsAdditionalList() || $subscriber->getIsOnlyUpdate() ||
						($subscriber->getSubscriberStatus() && $subscriber->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED))? true : false ;

		if($member){
			$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_UNSUBSCRIBE);
			if($member['status'] == 'subscribed'){
				if($isSubscribed && !$subscriber->getToUnsubscribe()){
					$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_UPDATE);
				}
			}else{
				if($isSubscribed){
					$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_RESUBSCRIBE);
					if($subscriber->getisSilentUpdate()){
						$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_SILENTUPDATE);
					}
				}else{
					$subscriber->setAction(null);
				}
			}
		}else{
			if($isSubscribed){
				$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_SUBSCRIBE);
			}elseif($subscriber->getSubscriberStatus() && $subscriber->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE){
				$subscriber->setAction(Ebizmarts_Mailchimp_Model_Mailchimp::ACTION_SILENTSUBSCRIBE);
			}
		}

		if($subscriber->getAction()){
			$subscriber->setRegistered($this->isMailchimpSubscribed($subscriber));
			$memberId = ($subscriber->getRegistered()->getData())? $subscriber->getRegistered()->getMemberId() : $subscriber->getEmail();
			$subscriber->setIdentifier($memberId);
			$mainModel->setCustomer($subscriber);
			$mainModel->mainAction();
		}
		return true;
	}

	public function webHookFilter($data){

		$currentStore = Mage::app()->getDefaultStoreView()->getStoreId();
		if($this->mailChimpEnabled($currentStore)){
			$lists = $this->getAvailablelists($currentStore);
			$lists = (is_array($lists))? $lists : array();
			$generalList = $this->getGeneralConfig('general',$currentStore);
			$lists[$generalList] = $generalList;

			if(array_key_exists($data['data']['list_id'],$lists)){

				$customer = Mage::getSingleton('mailchimp/subscripter')->prepareCustomer($data);

				if($customer->getMailchimpproId() || $customer->getAction() == 'subscribe'){
					Mage::getSingleton('mailchimp/subscripter')->registerInfo($customer);
				}

				$email = (isset($data['data']['email']))? $data['data']['email'] : $data['data']['old_email'] ;

				if($customer->getAction() == 'subscribe' && !$this->isSubscribed($email) && $data['data']['list_id'] == $generalList){
					Mage::getModel('mailchimp/subscriber')->subscribe($email,true);
				}elseif(($customer->getAction() == 'unsubscribe' || $customer->getAction() == 'cleaned' ) && $data['data']['list_id'] == $generalList && $this->isSubscribed($email)){
					Mage::getModel('newsletter/subscriber')->loadByEmail($email)->unsubscribe();
				}
			}
		}
	}

 	public function addException(Exception $e){

		$currentStore = Mage::app()->getStore()->getStoreId();

		foreach(explode("\n", $e->getMessage()) as $message) {
    		if ($currentStore == 0){
            	Mage::getSingleton('adminhtml/session')->addError($this->__('Mailchimp General Error: ').$message);
    		}else{
    			Mage::getSingleton('customer/session')->addError($this->__('An error occurred while saving your subscription, please try again later.'));
    		}
    		$this->logInfo($e, $this->__('Mailchimp General Error: ').$message);
        }
	}

	private function logInfo(Exception $exception, $alternativeText){

        $message = sprintf('Exception code: '.$exception->getCode().' \n Exception message: '.$exception->getMessage().' \n Trace: '.$exception->getTraceAsString());
        Mage::log($message, Zend_Log::DEBUG, 'mailChimp_Exceptions.log');

        return $this;
    }

}
?>
