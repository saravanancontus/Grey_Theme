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
class Contus_Merchant_Model_Merchant extends Mage_Core_Model_Abstract
{
    const XML_PATH_EMAIL_TEMPLATE   = 'catalog/email/email_template';
    const MERCHANT_PATH_NEW_TEMPLATE   = 'merchant/email/newaccount_template';
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'merchant/email/sender_email_identity';

    public function _construct()
    {
        parent::_construct();
        $this->_init('merchant/merchant');
    }
    public function registerURL()
    {
        $addedon=date('Y-m-d H:i:s');
        $firstname = Mage::app()->getRequest()->getPost('firstname');
        $lastname = Mage::app()->getRequest()->getPost('lastname');
        $email = Mage::app()->getRequest()->getPost('email');
        $username = Mage::app()->getRequest()->getPost('username');
        $city = Mage::app()->getRequest()->getPost('city');
        $pswd = Mage::app()->getRequest()->getPost('password');
        $password = Mage::helper('core')->getHash(Mage::app()->getRequest()->getPost('password'),2);
        $pswd = Mage::app()->getRequest()->getPost('password');
        $confirm = Mage::app()->getRequest()->getPost('confirmation');
        $tprefix = (string)Mage::getConfig()->getTablePrefix();
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $session = Mage::getSingleton('core/session');
        $error = '';

        $val = $read->query("select role_id from ".$tprefix."admin_role where role_name='merchant'");
        $count = $val->fetch();
        if($count == 0)
        {
            $write->query("insert into ".$tprefix."admin_role values('','0','1','0','G','0','merchant')");
            $mId = $write->lastInsertId();
            $write->query("insert into ".$tprefix."admin_user values ('','".$firstname."','".$lastname."','".$email."','".$username."','".$password."','".$addedon."','".$addedon."','','','','1','".$city."','','')");
            $result = $write->query("select * from ".$tprefix."admin_user where username='".$username."'");
            $row = $result->fetch() ;
            $userid = $row['user_id'];
            $write->query("insert into ".$tprefix."admin_role values ('','".$mId."','2','0','U','".$userid."','".$username."')");
            $this->sendNewAccountEmail($email,$username,$pswd);
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('merchant')->__('Registration Success!'));
        }
        else
        {
            $mId = $count['role_id'];
            $write->query("insert into ".$tprefix."admin_user values ('','".$firstname."','".$lastname."','".$email."','".$username."','".$password."','".$addedon."','".$addedon."','','','','1','".$city."', '','')");
            $result = $write->query("select * from ".$tprefix."admin_user where username='".$username."'");
            $row = $result->fetch() ;
            $userid = $row['user_id'];
            $write->query("insert into ".$tprefix."admin_role values ('','".$mId."','2','0','U','".$userid."','".$username."')");
            $this->sendNewAccountEmail($email,$username,$pswd);
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('merchant')->__('Registration Success!'));
        }
    }
     /*
     * Contus
     * Sending Mail for Merchant Account Creation
     */
    public function sendNewAccountEmail($email,$name,$pswd)
    {
        $postObject = new Varien_Object();

        $postObject->setData(array('email' => $email,'name' => $name,'password'=>$pswd));

        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
        $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
        $mailTemplate->setTemplateSubject('Coupon Confirmation From '.Mage::getStoreConfig('design/head/default_title'));

                        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
        ->sendTransactional(
            Mage::getStoreConfig(self::MERCHANT_PATH_NEW_TEMPLATE),
            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
            $email,
            Mage::getStoreConfig('design/head/default_title'),
            array('merchant' => $postObject)
        );

    }


}