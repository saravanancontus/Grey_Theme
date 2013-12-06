<?php
class Contus_Merchant_Model_Observer extends Mage_Core_Model_Abstract 
{
	 
    public function saveMerchantDeal($observer){
        $product = $observer->getProduct();
        $dealId = $product->getId();
        $merchant = $product->getMerchant();
        $resource = Mage::getSingleton('core/resource');
        $dealPayment = $resource->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $adminApproved = $dealPayment->fetchRow("Select product_id  from " . $tprefix . "deal_payment where product_id = " . $dealId . " and admin_approved='yes'");
        $admincount = count($adminApproved['product_id']);
        $adminuser = Mage::getSingleton('admin/session')->getUser();
        $roleId = implode('', $adminuser->getRoles());
        if ($roleId != 1 && $merchant != 1 && $admincount == 0)
        { 
            $resultproductid = $dealPayment->query("update  " . $tprefix . "catalog_product_entity_int set value=2 where entity_id  = '$dealId'");
        }
//        if ($roleId == 1 && $merchant == 2 && $admincount == 0) { 
//                $resultproductid = $dealPayment->query("update  " . $tprefix . "catalog_product_entity_int set value=2 where entity_id  = '$dealId'");
//
//                  }
       
        if($roleId == 1 && $merchant != 1 && $admincount == 0)
        {
            $resultproductid = $dealPayment->query("update  " . $tprefix . "catalog_product_entity_int set value=2 where entity_id  = '$dealId'");
            
        }
    }
      
}