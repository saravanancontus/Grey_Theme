<?php

class Ebizmarts_Mailchimp_Model_WebHooks extends Ebizmarts_Mailchimp_Model_MCAPI{

	protected $_actions = array();
	protected $_sources = array();

	private function setWebHookParams($list = ''){

		if(is_array($list)){
			$this->_actions['subscribe'] = (bool)$list['subscribe'];
			$this->_actions['unsubscribe'] = (bool)$list['unsubscribe'];
			$this->_actions['profile'] = (bool)$list['profile'];
			$this->_actions['cleaned'] = (bool)$list['cleaned'];
			$this->_actions['upemail'] = (bool)$list['upemail'];
			$this->_sources['user'] = (bool)$list['user'];
			$this->_sources['admin'] = (bool)$list['admin'];
			$this->_sources['api'] = (bool)$list['api'];
		}
		return $this;
	}

	public function mainWebHooksAction($listId){

		$helper = Mage::helper('mailchimp');
		$apikey = $helper->getApiKey();
		if(!$apikey){
			return false;
		}

		$this->MCAPI($apikey);
		$webHookUrl = str_replace('index.php/','',Mage::getBaseUrl()).'WebHooksCapture.php';

		if(is_array($listId)){
			$list = array();

			foreach($listId as $val){
				$list[substr($val,0,strpos($val,'='))] = substr($val,strpos($val,'=')+1,strlen($val));
			}

			$this->setWebHookParams($list);
			try {
				$this->listWebhookDel($list['list_id'],$webHookUrl);
				$response = $this->listWebhookAdd($list['list_id'],$webHookUrl,$this->_actions,$this->_sources);
			}catch (Exception $e) {
				$helper->addException($e);
	        }
		}else{
			try {
				$response = $this->listWebhooks($listId);
			}catch (Exception $e) {
				$helper->addException($e);
	        }
		}

		if($response && is_array($response) && count($response)){
			foreach($response as $hook){
				if($hook['url'] == $webHookUrl){
					return $hook;
				}
			}
		}

		return false;
	}

}
