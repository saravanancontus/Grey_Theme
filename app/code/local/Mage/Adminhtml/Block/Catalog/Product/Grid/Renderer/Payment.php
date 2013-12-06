<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Mage_Adminhtml_Block_Catalog_Product_Grid_Renderer_Payment extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $dealId = $row->getId();
        $dealName = $row->getName();
        $amount = floor(Mage::getStoreConfig('merchant/amount/payment'));
        $currencyCode = Mage::getStoreConfig('merchant/amount/currencycode');
        $merchant = $row->getMerchant();
        //$userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $adminuser = Mage::getSingleton('admin/session')->getUser();
        $roleId = implode('', $adminuser->getRoles());
        $userId = $adminuser->getId();

        $userEmail = Mage::getSingleton('admin/session')->getUser()->getEmail();
        $baseURl = Mage::getBaseUrl();
        $baseURl = str_replace('index.php/', '', $baseURl);
        $returnURL = $baseURl . 'admin/catalog_product/';
        $cancelURL = $baseURl . 'admin/catalog_product/';
        $ipnURL = $baseURl . 'gc/gc.php';
        $address = $this->htmlEscape($row->getCustomersite());

        //checking the product's payment
        $resource1 = Mage::getSingleton('core/resource');
        $dealPayment = $resource1->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        $productStatus = $dealPayment->fetchRow("Select product_id  from " . $tprefix . "deal_payment where product_id = " . $dealId);
        $count = count($productStatus['product_id']);
        $adminApproved = $dealPayment->fetchRow("Select product_id  from " . $tprefix . "deal_payment where product_id = " . $dealId . " and admin_approved='yes'");
        $admincount = count($adminApproved['product_id']);
        if ($roleId != 1) {
            if ($admincount == 0) {
                $resultproductid = $dealPayment->query("update  " . $tprefix . "catalog_product_entity_int set value=2 where entity_id  = '$dealId' and attribute_id = '96' ");
            }
        }
        $merchantId = $dealPayment->fetchRow("Select value from " . $tprefix . "core_config_data WHERE path = 'paypal/general/business_account'");
        $businessAccount = $merchantId['value'];
        $formAction = 'https://www.paypal.com/cgi-bin/webscr';
        
        //product not in table
        if ($count == 0) {
            //if it is admin grid
            if ($roleId == 1) {
                if ($merchant == $userId || $merchant == '') {
                    return '';
                } else{
                    $url = Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/activation/",array("id"=>$row->getId()));
                    $href = Mage::getBaseUrl() . "admin/catalog_product/activation/?id={$row->getId()}";
                    return '<a href="' . $url . '" target="_self">' . $this->__('Activate') . '</a>';
                }
            }
            //if it is merchant grid
            else {
                return '<form action="' . $formAction . '" method="post">
               <!-- Identify your business so that you can collect the payments. -->
               <input type="hidden" name="business" value="' . $businessAccount . '">
               <!-- Specify a Buy Now button. -->
               <input type="hidden" name="cmd" value="_xclick">
               <!-- Specify details about the item that buyers will purchase. -->
               <input type="hidden" name="item_number" value="' . $dealId . '">
               <input type="hidden" name="item_name" value="' . $dealName . '">
               <input type="hidden" name="amount" value="' . $amount . '">
               <input type="hidden" name="on0" value="' . $userId . '">
               <input type="hidden" name="on1" value="' . $userEmail . '">
               <input type="hidden" name="currency_code" value="' . $currencyCode . '">
               <input type="hidden" name="address1" value="' . $address . '">
               <input type="hidden" name="return" value="' . $returnURL . '">
               <input type="hidden" name="cancel_return" value="' . $cancelURL . '">
              <input type="hidden" name="notify_url" value="' . $ipnURL . '">
               <!-- Display the payment button. -->
               <button class="btnnew" type="submit" name="adminactivation" value="Activate"><span>' . $this->__("Activate") . '</span></button>
               </form>';
            }
        }
        //product in table
        else {
            //if it is admin grid
                            if ($admincount > 0) {
                    return '<span style="color:green;font-weight:bold">Activated</span>';
                } else {
                	 $url = Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/approve/",array("id"=>$row->getId()));
                    $href = Mage::getBaseUrl() . "admin/catalog_product/approve/?id={$row->getId()}";
                    return '<span style="color:green;font-weight:bold">PAID</span><br><a href="' . $url . '" target="_self">' . $this->__('Approve') . '</a>';
                }
          
            
            
        }
    }

}
