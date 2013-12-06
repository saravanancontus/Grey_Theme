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

class Gclone_Deal_Block_Newsletter extends Mage_Customer_Block_Account_Dashboard // Mage_Core_Block_Template
{

     public function getCustomerSubscription() {
        $customerId = 0;
        $customerId = Mage::getSingleton('customer/session')->getId();
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('newsletter_read');
        $subscriberTable = $resource->getTableName('newsletter/subscriber');

        $select = $read->select()
                        ->from(array('cp' => $subscriberTable))
                        ->where('cp.customer_id in (?)', $customerId);
        $subscriber = $read->fetchAll($select);

        return $subscriber;
    }

 public function getCityToSubscribe($city){
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'dealcity');
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId);
        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
            $value = $option['value'];
            if ($value != '') {
                if ($city != $option['value']) {
                    $cities[$value] = $option['label'];
                    $select .= "<option value='" . $option['value'] . "'>" . $option['label'] . "</option>";
                }
            }
        }
        return $select;
    }
    
    public function getCustomerEmail(){
        $customerId = Mage::getSingleton('customer/session')->getId();
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('customer_read');
        $customerTable = $resource->getTableName('customer/entity');

        $select = $read->select()
                        ->from(array('cp' => $customerTable))
                        ->where('cp.entity_id in (?)', $customerId);
        $email = $read->fetchAll($select);
        return $email;
    }

}
