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

require_once 'Mage/Checkout/controllers/OnepageController.php';

class Gclone_Deal_Checkout_OnepageController extends Mage_Checkout_OnepageController {

	/**
	 * Checkout page
	 */
	
	public function saveBillingAction()
	{
		
		if ($this->_expireAjax()) {
			return;
		}
		if ($this->getRequest()->isPost()) {
			//            $postData = $this->getRequest()->getPost('billing', array());
			//            $data = $this->_filterPostData($postData);
			$data = $this->getRequest()->getPost('billing', array());
			$customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

			if (isset($data['email'])) {
				$data['email'] = trim($data['email']);
			}
			$result = $this->getOnepage()->saveBilling($data, $customerAddressId);

			if (!isset($result['error'])) {//
				 
				 
				 
				
				 
				 
				$session= Mage::getSingleton('checkout/session');
				foreach($session->getQuote()->getAllItems() as $item)
				{

					$productids[] = $item->getProductId();

				}
	$session= Mage::getSingleton('checkout/session');
				foreach($session->getQuote()->getAllItems() as $item)
				{

					$productids[] = $item->getProductId();

				}
				
				foreach ($productids as $productid )
				{
					$product_details = Mage::getModel('catalog/product')->load($productid);
					$product_weight = $product_details->getWeight();

					if($product_weight == 0)
					{
						$result['goto_section'] = 'payment';
					$result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
					);
					}else if ($this->getOnepage()->getQuote()->isVirtual()) {
					$result['goto_section'] = 'payment';
					$result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
					);
				} elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
					 
					$result['goto_section'] = 'shipping_method';
					$result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
					);

					$result['allow_sections'] = array('shipping');
					$result['duplicateBillingInfo'] = 'true';
				}else {
					$result['goto_section'] = 'shipping';
				}
				
				 
				/* check quote for virtual */
				
					
					
			
				
				}
					
					
					
				


			}//

			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		}
	}
	
	public function indexAction() {
		if (!Mage::helper('checkout')->canOnepageCheckout()) {
			Mage::getSingleton('checkout/session')->addError($this->__('The onepage checkout is disabled.'));
			$this->_redirect('checkout/cart');
			return;
		}
		$quote = $this->getOnepage()->getQuote();
		if (!$quote->hasItems() || $quote->getHasError()) {
			$this->_redirect('checkout/cart');
			return;
		}
		if (!$quote->validateMinimumAmount()) {
			$error = Mage::getStoreConfig('sales/minimum_order/error_message');
			Mage::getSingleton('checkout/session')->addError($error);
			$this->_redirect('checkout/cart');
			return;
		}

		//Maximum one product to add the shopping cart
		$params = $this->getRequest()->getParams();
		$session = Mage::getSingleton('checkout/session');
		$productId = "";
		foreach ($session->getQuote()->getAllItems() as $item) {
			$productId = $item->getProductId();
		}
		$cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();
		if ($params['product'] != $productId) {
			if ($cartItems > 1) {
				Mage::getSingleton('checkout/session')->addError($this->__('Maximum one product to add the shopping cart.'));
				$this->_redirect('checkout/cart');
				return;
			}
		}

		Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
		Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
		$this->getOnepage()->initCheckout();
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
		$this->renderLayout();
	}

	/**
	 * Order success action
	 */
	public function successAction() {
		$session = $this->getOnepage()->getCheckout();
		if (!$session->getLastSuccessQuoteId()) {
			$this->_redirect('checkout/cart');
			return;
		}

		$lastQuoteId = $session->getLastQuoteId();
		$lastOrderId = $session->getLastOrderId();
		/* contus new */
		$resource1 = Mage::getSingleton('core/resource');
		$currentdeal = $resource1->getConnection('core_write');
		$tprefix = (string) Mage::getConfig()->getTablePrefix();

		$order = Mage::getModel('sales/order')->load($lastOrderId);
		$incrementId = $order->getIncrementId();
		$items = $order->getAllItems();
		$products = array();

		foreach ($items as $itemId => $item) {
			$model = Mage::getModel('catalog/product');
			$productId = $item->getProductId();
			$productdetail = $model->load($item->getProductId());
			$incrementId = $productdetail->getOrderPrefix() . $incrementId;
			$merchantId = $productdetail->getMerchant();
		}
		$resultproductid = $currentdeal->query("update  " . $tprefix . "sales_flat_order set increment_id='$incrementId', merchant_id='$merchantId' where entity_id  = '$lastOrderId'");
		$resultproductid = $currentdeal->query("update  " . $tprefix . "sales_flat_order_grid set increment_id='$incrementId', merchant_id='$merchantId' where entity_id  = '$lastOrderId'");
		$lastRecurringProfiles = $session->getLastRecurringProfileIds();
		if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
			$this->_redirect('checkout/cart');
			return;
		}

		$session->clear();
		$this->loadLayout();
		$this->_initLayoutMessages('checkout/session');
		Mage::dispatchEvent('checkout_onepage_controller_success_action');
		$this->renderLayout();
	}

}
