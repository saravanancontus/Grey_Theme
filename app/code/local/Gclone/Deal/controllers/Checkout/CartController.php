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
/**
 * Shopping cart controller
 */
require_once 'Mage/Checkout/controllers/CartController.php';

class Gclone_Deal_Checkout_CartController extends Mage_Checkout_CartController {

    /**
     * Add product to shopping cart action
     */
    public function addAction() {

        $cart = $this->_getCart();
        $params = $this->getRequest()->getParams();
        /* start
         * coding used for restricting user to add more than one 1 item in the cart.
         *
         *
         */
        $session = Mage::getSingleton('checkout/session');

        $productId = "";

        foreach ($session->getQuote()->getAllItems() as $item) {

            $productId = $item->getProductId();
        }

        $cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();
        if ($params['product'] != $productId) {
            if ($cartItems >= 1) {
                $this->_getSession()->addError($this->__('Maximum one item to add the shopping cart.'));
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

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                            array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()) {
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
                if (isset($_POST['giftoption']) && $_POST['giftoption'] == "gift") {
                    Mage::getSingleton('core/session')->setProduct_Id($_POST['productid']);
                    $this->_redirect('checkout/cart/#gift');
                } else {
                    $this->_goBack();
                }
            }
        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
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
            $resource = Mage::getSingleton('core/resource');
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $read = Mage::getSingleton('core/resource')->getConnection('read');

            $fromName = (string) $this->getRequest()->getParam('order_gift_from_name');
            $toName = (string) $this->getRequest()->getParam('order_gift_to_name');
            $message = (string) $this->getRequest()->getParam('order_gift_message');
            $email = (string) $this->getRequest()->getParam('order_gift_recipient_email');
            $couponTo = $this->getRequest()->getParam('coupon_to');
            $couponTo = 11;

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

            $getGiftDetail = $read->fetchRow("Select gift_message_id ,email,recipient,order_id,type  from " . $tprefix . "gift_message  where customer_id = '$customerId' AND product_id ='$productId' AND order_id = 0");

            /* Gift Option Already exist Update else Insert as New Record */
            if (isset($getGiftDetail['email'])) {
                $write->update($resource->getTableName('gift_message'),
                             array('sender' => addslashes($fromName),'recipient' => addslashes($toName),'message' => addslashes($message), 'email' => $email, 'type' => $couponTo),
                             array('customer_id = ?' => $customerId,'product_id = ?' => $productId,'order_id = ?' => '0'));

                $this->_getSession()->addSuccess($this->__('Gift was Updated successfully.'));
            } else {
                $insert = $write->insert(
                       $resource->getTableName('gift_message'),
                       array('customer_id' => $customerId, 'sender' => addslashes($fromName), 'recipient' => addslashes($toName), 'message' => addslashes($message), 'email' => $email, 'product_id' => $productId, 'type' => $couponTo)
                );
                $this->_getSession()->addSuccess($this->__('Gift was Added successfully.'));
            }
            $this->_goBack();
            /* Redirect */
        } else {
            $this->_getSession()->addError($this->__('Please Login to make use of Gift Option.'));
            $this->_goBack();
        }
    }

    public function giftDeleteAction() {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $sessiongift = Mage::getSingleton('core/session');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $productId = Mage::getSingleton('core/session')->getBuyProductId();
        $resource = Mage::getSingleton('core/resource');
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        if ($customerId != '') {
            $write->delete($resource->getTableName('gift_message'),
                   array('customer_id = ?'=> $customerId, 'product_id = ?' => $productId,'order_id = ?' => '0')
                );
            $this->_getSession()->addSuccess($this->__('Gift was Deleted successfully.'));
            $this->_goBack();
            $sessiongift->setorderemail('');
            $sessiongift->setorderfrom('');
            $sessiongift->setorderto('');
            $sessiongift->setordermessage('');
        }
    }

}
