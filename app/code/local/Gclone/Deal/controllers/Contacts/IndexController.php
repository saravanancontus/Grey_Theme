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

require_once 'Mage/Contacts/controllers/IndexController.php';

class Gclone_Deal_Contacts_IndexController extends Mage_Contacts_IndexController {
    
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'contacts/email/email_template';
    const XML_PATH_ENABLED = 'contacts/contacts/enabled';

    public function postAction() {
        
        require_once('recaptchalib.php');
        $privatekey = "6LdGMr8SAAAAALFCIlZ9puPWbkgsQo_CfLuo0HEs";
        $resp = recaptcha_check_answer($privatekey,
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["recaptcha_challenge_field"],
                        $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) {
            // What happens when the CAPTCHA was entered incorrectly
            Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__("The reCAPTCHA wasn't entered correctly. Try it again."));
            $this->_redirect('*/*/');
            return;
        } else {
            $post = $this->getRequest()->getPost();
            if ($post) {
                $translate = Mage::getSingleton('core/translate');
                /* @var $translate Mage_Core_Model_Translate */
                $translate->setTranslateInline(false);
                try {
                    $postObject = new Varien_Object();
                    $postObject->setData($post);

                    $error = false;

                    if (!Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                        $error = true;
                    }

                    if (!Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
                        $error = true;
                    }

                    if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                        $error = true;
                    }

                    if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                        $error = true;
                    }

                    if ($error) {
                        throw new Exception();
                    }
                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->setReplyTo($post['email'])
                            ->sendTransactional(
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                                    null,
                                    array('data' => $postObject)
                    );

                    if (!$mailTemplate->getSentSuccess()) {
                        throw new Exception();
                    }

                    $translate->setTranslateInline(true);

                    Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                    $this->_redirect('*/*/');

                    return;
                } catch (Exception $e) {
                    $translate->setTranslateInline(true);

                    Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                    $this->_redirect('*/*/');
                    return;
                }
            } else {
                $this->_redirect('*/*/');
            }
        }
    }

}
