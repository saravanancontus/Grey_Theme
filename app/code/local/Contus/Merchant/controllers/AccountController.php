<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */
class Contus_Merchant_AccountController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {       
        $this->loadLayout();
        $this->renderLayout();
    }
    public function createAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        try
        {
            Mage::getModel('merchant/merchant')->registerURL();
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError(Mage::helper('merchant')->__('User Already Exists!'));
            $this->_redirect('*/*/create');
            return;
        }
        $this->addError($msg);

        $this->_redirectError(Mage::getUrl('*/', array('_secure' => false)));
    }

   
    /**
     * Adding new message to message collection
     *
     * @param   Mage_Core_Model_Message_Abstract $message
     * @return  Mage_Core_Block_Messages
     */
    public function addMessage(Mage_Core_Model_Message_Abstract $message)
    {
        $this->getMessageCollection()->add($message);
        return $this;
    }

    /**
     * Adding new error message
     *
     * @param   string $message
     * @return  Mage_Core_Block_Messages
     */
    public function addError($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->error($message));
        return $this;
    }
    public function getMessageCollection()
    {
        if (!($this->_messages instanceof Mage_Core_Model_Message_Collection)) {
            $this->_messages = Mage::getModel('core/message_collection');
        }
        return $this->_messages;
    }
}