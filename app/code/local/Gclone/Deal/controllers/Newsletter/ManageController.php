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

require_once 'Mage/Newsletter/controllers/ManageController.php';

class Gclone_Deal_Newsletter_ManageController extends Mage_Newsletter_ManageController {

    public function saveAction()
    { 
        if (!$this->_validateFormKey()) {
            return $this->_redirect('customer/account/');
        }
        try {

            $city= $this->getRequest()->getParam('city');
            $email = $this->getRequest()->getParam('email');
            if($city != '0'){
            $status=Mage::getModel('newsletter/subscriber')->subscribe($email,$city);
            if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    Mage::getSingleton('customer/session')->addSuccess($this->__('Confirmation request has been sent.'));
                }
                else {
                    Mage::getSingleton('customer/session')->addSuccess($this->__('Thank you for your subscription.'));
                }
            }
            else{
               Mage::getSingleton('customer/session')->addError($this->__('Save operation cannot be performed, because no change has been made to save.'));
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError($this->__('An error occurred while saving your subscription.'));
        }
        $this->_redirect('customer/account/');
    }

}
