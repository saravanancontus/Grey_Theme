<?php

require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'Apptha/Onestepcheckout/controllers/IndexController.php';

class Apptha_Onestepcheckout_Checkout_CartController extends Mage_Checkout_CartController {

    public function addAction() {

        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        /* start
         * coding used for restricting user to add more than one 1 item in the cart.
         *
         *
         */
        $session = Mage::getSingleton('checkout/session');
        Mage::getSingleton('core/session')->setCartprodid($params['product']);
        $productId = "";

        foreach ($session->getQuote()->getAllItems() as $item) {

            $productId = $item->getProductId();
        }

        $cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();
        if ($cartItems >= 1) {
            if ($params['product'] != $productId) {

                Mage::getSingleton('core/session')->addError($this->__('Maximum one item to add the shopping cart.'));
                $this->_goBack();
                return;
            }
        }
        
        /* end */
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                                array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }

            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            Mage::getSingleton('core/session')->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete', array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!Mage::getSingleton('core/session')->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    Mage::getSingleton('core/session')->addSuccess($message);
                }
                if (isset($_POST['giftoption']) && $_POST['giftoption'] == "gift") {
                    Mage::getSingleton('core/session')->setProduct_Id($_POST['productid']);
                    $this->_redirect('checkout/cart/#gift');
                } else {
                    $this->_goBack();
                }
            }
        } catch (Mage_Core_Exception $e) {
            if (Mage::getSingleton('core/session')->getUseNotice(true)) {
                Mage::getSingleton('core/session')->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    Mage::getSingleton('core/session')->addError($message);
                }
            }

            $url = Mage::getSingleton('core/session')->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addException($e, $this->__('Cannot add the item to shopping cart.'));
            $this->_goBack();
        }
    }

    public function giftPostAction() {
        /* Retriving Session values */
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $sessiongift = Mage::getSingleton('core/session');
        /* Table Prefix */
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        /* Current product Id */
        //$productId=Mage::getSingleton('core/session')->getProductId();
        $productId = Mage::getSingleton('core/session')->getBuyProductId();
        //$productId=Mage::getSingleton('core/session')->getProduct_Id();
        if ($customerId != '') {
            /* fetch write database connection that is used in Mage_Core module */
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $read = Mage::getSingleton('core/resource')->getConnection('read');

            $fromName = (string) $this->getRequest()->getParam('order_gift_from_name');
            $toName = (string) $this->getRequest()->getParam('order_gift_to_name');
            $message = (string) $this->getRequest()->getParam('order_gift_message');
            $email = (string) $this->getRequest()->getParam('order_gift_recipient_email');
            $couponTo = $this->getRequest()->getParam('coupon_to');

            /* Storing the session Values */
            if ($this->getRequest()->getParam('order_gift_from_name') != '')
                $sessiongift->setorderfrom($fromName);
            if ($this->getRequest()->getParam('order_gift_to_name') != '')
                $sessiongift->setorderto($toName);
            if ($this->getRequest()->getParam('order_gift_message') != '')
                $sessiongift->setordermessage($message);
            if ($this->getRequest()->getParam('order_gift_recipient_email') != '')
                $sessiongift->setorderemail($email);
            if ($this->getRequest()->getParam('coupon_to') != '')
                $sessiongift->setcouponto($couponTo);

            $getGiftDetail = $read->fetchRow("Select gift_message_id ,email,recipient,order_id  from " . $tprefix . "gift_message  where customer_id = '$customerId' AND product_id ='$productId' AND order_id = 0");

            /* Gift Option Already exist Update else Insert as New Record */
            if (isset($getGiftDetail['email'])) {
                $write->query("UPDATE " . $tprefix . "gift_message set sender ='" . addslashes($fromName) . "',recipient='" . addslashes($toName) . "',message='" . addslashes($message) . "',email='" . $email . "',type='" . $couponTo . "' where customer_id = '" . $customerId . "' AND product_id ='$productId' AND order_id = 0");
                Mage::getSingleton('core/session')->addSuccess($this->__('Gift was Updated successfully.'));
            } else {
                $write->query("insert into " . $tprefix . "gift_message (customer_id,sender,recipient,message,email,product_id,type)values ($customerId,'" . addslashes($fromName) . "','" . addslashes($toName) . "','" . addslashes($message) . "','" . $email . "','" . $productId . "','" . $couponTo . "')");
                Mage::getSingleton('core/session')->addSuccess($this->__('Gift was Added successfully.'));
            }
            $this->_goBack();
            /* Redirect */
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Please Login to make use of Gift Option.'));
            $this->_goBack();
        }
    }

    public function giftDeleteAction() {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $sessiongift = Mage::getSingleton('core/session');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $productId = Mage::getSingleton('core/session')->getBuyProductId();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        if ($customerId != '') {
            $write->query("Delete from " . $tprefix . "gift_message  where customer_id = '" . $customerId . "' AND product_id ='$productId' AND order_id = 0");
            Mage::getSingleton('core/session')->addSuccess($this->__('Gift was Deleted successfully.'));
            $this->_goBack();
            $sessiongift->setorderemail('');
            $sessiongift->setorderfrom('');
            $sessiongift->setorderto('');
            $sessiongift->setordermessage('');
        }
    }

    public function indexAction() {
        $quote = $this->getOnepage()->getQuote();
        $cart = $this->_getCart();
        if (!$quote->validateMinimumAmount()) {

            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        $messages = array();
        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                $messages[] = $message;
            }
        }
        $cart->getCheckoutSession()->addUniqueMessages($messages);

        $cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();
        if ($cartItems > 1) {
            $cartHelper = Mage::helper('checkout/cart');
            $items = $cartHelper->getCart()->getItems();
            foreach ($items as $item) {
                $itemId = $item->getItemId();
                $cartHelper->getCart()->removeItem($itemId)->save();
                Mage::getSingleton('core/session')->addError($this->__('Maximum one item to add the shopping cart.'));
                $this->_goBack();
                return;
            }
        }



        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
        /* save billing and shipping information onload */
        $helper = Mage::helper('onestepcheckout/checkout');
        $this->shippingreloadAction();
        $billing_data = $this->getRequest()->getPost('billing', array());
        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        if ($this->getOnepage()->getQuote()->isVirtual()) {
            $Billingdata = $helper->load_add_data($billing_data);
            $billing_result = $this->getOnepage()->saveBilling($Billingdata, $customerAddressId);
        } else {
            if (!empty($billing_data['use_for_shipping'])) {
                $Shippingdata = $helper->load_add_data($billing_data);
                $shipping_result1 = $this->getOnepage()->saveBilling($Shippingdata, $customerAddressId);
                $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $customerAddressId);
            } else {
                $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                $shipping_data = $this->getRequest()->getPost('shipping', array());
                $Shippingdata = $helper->load_add_data($shipping_data);
                $shipping_result1 = $this->getOnepage()->saveBilling($Shippingdata, $customerAddressId);
                $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $shippingAddressId);
            }
        }
        $this->_checkCountry();
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
        /* End of save billing and shipping information onload */
    }

    private function _checkCountry() {

        //$onepage = $this->getOnepage();
        $quote = $this->getOnepage()->getQuote();
        $shipping = $quote->getShippingAddress();
        $billing = $quote->getBillingAddress();
        $default_country = false;
        $country_id = $shipping->getCountryId();
        $helper = Mage::helper('onestepcheckout/checkout');
        $enableGeoIp = Mage::getStoreConfig('onestepcheckout/general/enable_geoip');

        if ($enableGeoIp == 1) {
            if (!$helper->getGeoIp()->countryCode) {
                $countryId = Mage::getStoreConfig('onestepcheckout/general/default_country_id');
            } else {
                $countryId = $helper->getGeoIp()->countryCode;
            }
        } else {
            $countryId = Mage::getStoreConfig('onestepcheckout/general/default_country_id');
        }

        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }
        $shipping->setCountryId($countryId)->setCollectShippingRates(true)->save();
        $billing->setCountryId($countryId)->save();
        $shipping->setSameAsBilling(true)->save();
    }

    public function shippingreloadAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        if (!$shipping_method) {
            $shipping_method = Mage::getStoreConfig('onestepcheckout/general/default_shipping_method');
        }
        $save_shippingmethod = $this->getOnepage()->saveShippingMethod($shipping_method);
        if (!$save_shippingmethod) {
            $event = Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(),
                        'quote' => $this->getOnepage()->getQuote()));
            $this->getOnepage()->getQuote()->collectTotals();
        }
        $this->getOnepage()->getQuote()->collectTotals()->save();
        $this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
        //$this->loadLayout();
        //$this->renderLayout();
    }

    /* End of index Action */

    /* function:if ajax  expires  check the quouetes if not avaiable  redirect to ajaxredirectresponse  fn */

    protected function _expireAjax() {
        $activateInCart = Mage::getStoreConfig('onestepcheckout/general/Activate_apptha_onestepcheckout_cart');
        if ($activateInCart != 1):
            if (!$this->getOnepage()->getQuote()->hasItems()
                    || $this->getOnepage()->getQuote()->getHasError()
                    || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
                $this->_ajaxRedirectResponse();
                return true;
            }
            $action = $this->getRequest()->getActionName();
            if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
                    && !in_array($action, array('index', 'progress'))) {
                $this->_ajaxRedirectResponse();
                return true;
            }
            return false;
        endif;
    }

    /* End of expireAjax fn */

    /* function:set session expires and send the response to Onestepcheckout.js  */

    public function _ajaxRedirectResponse() {
        $this->getResponse()
                ->setHeader('HTTP/1.1', '403 Session Expired')
                ->setHeader('Login-Required', 'true')
                ->sendResponse();
        return $this;
    }

    /* End of ajaxRedirectResponse fn */

    /* function:includes the core checkout onepage model  */

    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /* End of getOnepage fn */

    /* function:get the username and password from ajax and check the user table  and send the result as json response to js */

    public function loginAction() {
        $username = $this->getRequest()->getPost('onestepcheckout_username', false);
        $password = $this->getRequest()->getPost('onestepcheckout_password', false);
        $session = Mage::getSingleton('customer/session');

        $result = array(
            'success' => false
        );

        if ($username && $password) {
            try {
                $session->login($username, $password);
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }

            if (!isset($result['error'])) {
                $result['success'] = true;
            }
        } else {
            $result['error'] = $this->__('Please enter your Email Id and password.');
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    /* End of Login Action  */

    public function forgotPasswordAction() {
        $email = $this->getRequest()->getPost('email', false);

        if (!Zend_Validate::is($email, 'EmailAddress')) {
            $result = array('success' => false);
        } else {

            $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success' => true);
                } catch (Exception $e) {
                    $result = array('success' => false, 'error' => $e->getMessage());
                }
            } else {
                $result = array('success' => false, 'error' => 'notfound');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    /* function:load the product information when payment method selects */

    public function playAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    /* End of Play Action  */

    /* function:load the product information when shipping method selects */

    public function reloadAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $shipping_method = $this->getRequest()->getPost('shipping_method', '');
        if (!$shipping_method) {
            $shipping_method = Mage::getStoreConfig('onestepcheckout/general/default_shipping_method');
        }
        $save_shippingmethod = $this->getOnepage()->saveShippingMethod($shipping_method);
        if (!$save_shippingmethod) {
            $event = Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(),
                        'quote' => $this->getOnepage()->getQuote()));
            $this->getOnepage()->getQuote()->collectTotals();
        }
        $this->getOnepage()->getQuote()->collectTotals()->save();
        $this->getOnepage()->getQuote()->getShippingAddress()->setShippingMethod($shipping_method);
        $this->loadLayout();
        $this->renderLayout();
    }

    /* End of reload Action  */

    /* Start of paymentreload Action  */
    /* payment reload when changes the shipping methods */

    public function paymentreloadAction() {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /* End of paymentreload Action  */

    public function summaryAction() {
        if ($this->_expireAjax()) {

            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    //ajax save billing function
    //save billing,shipping,payment information
    public function savebillingAction() {
        $billing_data = $this->getRequest()->getPost('billing', array());
        $shipping_data = $this->getRequest()->getPost('shipping', array());

        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
        $quote = $this->getOnepage()->getQuote();
        $shipping = $quote->getShippingAddress();
        $billing = $quote->getBillingAddress();
        $billingCountryId = $billing_data['country_id'];
        $billingRegionId = $billing_data['region_id'];
        $billingZipcode = $billing_data['postcode'];
        $billingRegion = $billing_data['region'];
        $billingCity = $billing_data['city'];
        $this->getOnepage()->getQuote()->getShippingAddress()
                ->setCountryId($billingCountryId)
                ->setRegionId($billingRegionId)
                ->setPostcode($billingZipcode)
                ->setRegion($billingRegion)
                ->setCity($billingCity)
                ->setCollectShippingRates(true);
        $this->getOnepage()->getQuote()->getShippingAddress()->collectTotals();
        $this->getOnepage()->getQuote()->save();
        $shipping->setSameAsBilling(true)->save();

        if (!empty($shippingAddressId)) {

            $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
            if (is_object($shippingAddress) && $shippingAddress->getCustomerId() == Mage::helper('customer')->getCustomer()->getId()) {
                $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
            }
        }

        /* start of save billing and shipping information for tax calculation */

        $config = Mage::getStoreConfig('tax/calculation/based_on');
        $helper = Mage::helper('onestepcheckout/checkout');
        if ($config == "billing") {
            $billing_info = $helper->load_add_data($billing_data);
            $billing_result = $this->getOnepage()->saveBilling($billing_info, $customerAddressId);
        } else {
            if (!empty($billing_data['use_for_shipping'])) {
                $Billingdata = $helper->load_add_data($billing_data);
                $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
            } else {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $billing_info = $helper->load_add_data($billing_data);
                    $billing_result = $this->getOnepage()->saveBilling($billing_info, $customerAddressId);
                } else {
                    $shipping_info = $helper->load_add_data($shipping_data);
                    $shipping_result = $this->getOnepage()->saveShipping($shipping_info, $shippingAddressId);
                }
            }
        }


        /* End  of save billing and shipping information for tax calculation */


        //if shipping same as billing
        // save billing country,region,city,postcode to shipping
        if (!empty($billing_data['use_for_shipping'])) {
            if (!empty($billing_data['country_id'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($billing_data['country_id'])->setCollectShippingRates(true);
            }
            if (!empty($billing_data['region'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($billing_data['region'])->setCollectShippingRates(true);
            }
            if (!empty($billing_data['city'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setCity($billing_data['city'])->setCollectShippingRates(true);
            }
            if (!empty($billing_data['postcode'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($billing_data['postcode'])->setCollectShippingRates(true);
            }
        } else {
            if (!empty($shipping_data['country_id'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($shipping_data['country_id'])->setCollectShippingRates(true);
            } else {
                $this->getOnepage()->getQuote()->getBillingAddress()->setCountryId($shipping_data['country_id'])->setCollectShippingRates(true);
            }
            if (!empty($shipping_data['region'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($shipping_data['region'])->setCollectShippingRates(true);
            }
            if (!empty($shipping_data['city'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setCity($shipping_data['city'])->setCollectShippingRates(true);
            }
            if (!empty($shipping_data['postcode'])) {
                //$this->shippingreloadAction();
                $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($shipping_data['postcode'])->setCollectShippingRates(true);
            }
        }
        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        if ($this->getOnepage()->getQuote()->isVirtual()) {
            $this->getOnepage()->getQuote()->getBillingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        } else {
            $this->getOnepage()->getQuote()->getShippingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        }

        $this->loadLayout(false);
        $this->renderLayout();
    }

    /* function:get all the information from onepage form and save the order using ajax */

    public function saveOrderAction() {
        if ($this->_expireAjax()) {

            return;
        }
        $result = array();
        try {
            $helper = Mage::helper('onestepcheckout/checkout');
            if ($this->getRequest()->isPost()) {
                $Method = $this->getRequest()->getPost('checkout_method', false);
                $Billingdata = $this->getRequest()->getPost('billing', array());
                $Billingdata = $helper->load_exclude_data($Billingdata);
                $Paymentdata = $this->getRequest()->getPost('payment', array());
                $result = $this->getOnepage()->saveCheckoutMethod($Method);
                if (isset($Billingdata['is_subscribed']) && !empty($Billingdata['is_subscribed'])) {
                    $customer = $this->getOnepage()->getCheckout()->setCustomerIsSubscribed(1);
                }
                $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
                if (isset($Billingdata['email'])) {
                    $Billingdata['email'] = trim($Billingdata['email']);
                }
                $this->getOnepage()->getQuote()->getBillingAddress()->setCountryId($Billingdata['country_id'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setCity($Billingdata['city'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setRegionId($Billingdata['region_id'])->setCollectShippingRates(true);
                //$this->getOnepage()->getQuote()->getBillingAddress()->setRegionId($Billingdata['region'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setPostcode($Billingdata['postcode'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setFirstname($Billingdata['firstname'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setLastname($Billingdata['lastname'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setStreet($Billingdata['street'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setTelephone($Billingdata['telephone'])->setCollectShippingRates(true);
                $this->getOnepage()->getQuote()->getBillingAddress()->setEmail($Billingdata['email'])->setCollectShippingRates(true);

                $Billingresult = $this->getOnepage()->saveBilling($Billingdata, $customerAddressId);

                $Paymentresult = $this->getOnepage()->savePayment($Paymentdata);
                $Shippingdata = $this->getRequest()->getPost('shipping', array());
                $ShippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                if (!empty($Billingdata['use_for_shipping'])) {
                    $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($Billingdata['country_id'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setCity($Billingdata['city'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($Billingdata['region_id'])->setCollectShippingRates(true);
                    //$this->getOnepage()->getQuote()->getBillingAddress()->setRegionId($Billingdata['region'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($Billingdata['postcode'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setFirstname($Billingdata['firstname'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setLastname($Billingdata['lastname'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setStreet($Billingdata['street'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setTelephone($Billingdata['telephone'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setEmail($Billingdata['email'])->setCollectShippingRates(true);

                    $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
                } else if (!empty($ShippingAddressId)) {

                    $shippingAddress = Mage::getModel('customer/address')->load($ShippingAddressId);
                    if (is_object($shippingAddress) && $shippingAddress->getCustomerId() == Mage::helper('customer')->getCustomer()->getId()) {
                        $Shippingdata = array_merge($Shippingdata, $shippingAddress->getData());
                        $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $ShippingAddressId);
                    }
                } else if (empty($Billingdata['use_for_shipping']) && !$ShippingAddressId) {
                    $this->getOnepage()->getQuote()->getShippingAddress()->setCountryId($Shippingdata['country_id'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setCity($Shippingdata['city'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setRegionId($Shippingdata['region_id'])->setCollectShippingRates(true);
                    //$this->getOnepage()->getQuote()->getBillingAddress()->setRegionId($Billingdata['region'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setPostcode($Shippingdata['postcode'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setFirstname($Shippingdata['firstname'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setLastname($Shippingdata['lastname'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setStreet($Shippingdata['street'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setTelephone($Shippingdata['telephone'])->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->getShippingAddress()->setEmail($Shippingdata['email'])->setCollectShippingRates(true);
                    $shipping_result = $this->getOnepage()->saveShipping($Shippingdata, $ShippingAddressId);
                } else {
                    $shipping_result = $this->getOnepage()->saveShipping($Billingdata, $customerAddressId);
                }
                $ShippingMethoddata = $this->getRequest()->getPost('shipping_method', '');
                $ShippingMethodresult = $this->getOnepage()->saveShippingMethod($ShippingMethoddata);
                // }
            }
            Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(), 'quote' => $this->getOnepage()->getQuote()));
            $data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnepage()->savePayment($data);

            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if ($redirectUrl) {
                if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                    $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                    if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                        $result['success'] = false;
                        $result['error'] = true;
                        $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                        return;
                    }
                }
                $result['success'] = true;
                $result['error'] = false;
                $result['redirect'] = $redirectUrl;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                return;
            }

            // $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            $result = array();
            //try {
            if ($requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds()) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['error_messages'] = $this->__('Please agree to all the terms and conditions before placing the order.');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                    return;
                }
            }
            if ($data = $this->getRequest()->getPost('payment', false)) {
                $this->getOnepage()->getQuote()->getPayment()->importData($data);
            }
            $this->getOnepage()->saveOrder();

            $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
            $result['success'] = true;
            $result['error'] = false;
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $message = $e->getMessage();
            if (!empty($message)) {
                $result['error_messages'] = $message;
            }
        } catch (Mage_Core_Exception $e) {
            // Mage::logException($e);
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
        } catch (Exception $e) {
            // Mage::logException($e);
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $e->getMessage();
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['error'] = true;
            $result['error_messages'] = $this->__('There was an error processing your order. Please contact us or try again later.');
        }
        $this->getOnepage()->getQuote()->save();
        /**
         * when there is redirect to third party, we don't want to save order yet.
         * we will save the order in return action.
         */
        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }
        if ($result['success']) {
            $result['success'] = Mage::getBaseUrl() . 'checkout/onepage/success/';
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /* End of saveorder Action */

    /* start of couponcode Action */

    function couponcodeAction() {

        $quote = $this->getOnepage()->getQuote();
        $couponCode = (string) $this->getRequest()->getParam('code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }

        $response = array(
            'success' => false,
            'error' => false,
            'message' => false,
        );
        try {
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
                    ->collectTotals()
                    ->save();

            if ($couponCode) {
                if ($couponCode == $quote->getCouponCode()) {
                    $response['success'] = true;
                    $response['message'] = $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode));
                } else {
                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode));
                }
            } else {
                $response['success'] = true;
                $response['message'] = $this->__('Coupon code was canceled successfully.');
            }
        } catch (Mage_Core_Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        } catch (Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply coupon code.');
        }


        $html = $this->getLayout()
                ->createBlock('onestepcheckout/onestep_review_info')
                ->setTemplate('onestepcheckout/onestep/review/info.phtml')
                ->toHtml();

        $response['summary'] = $html;


        $this->getResponse()->setBody(Zend_Json::encode($response));
    }

    /* End of couponcode Action */

    public function replayAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $helper = Mage::helper('onestepcheckout/checkout');

        try {
            $payment = $this->getRequest()->getPost('payment', array());
            $helper->savePayment($payment);
        } catch (Exception $e) {
            //die('Error: ' . $e->getMessage());
            // Silently fail for now
        }

        $this->loadLayout(false);
        $this->renderLayout();
    }

}
