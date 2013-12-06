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
require_once 'Mage/Customer/controllers/AccountController.php';

class Gclone_Deal_Customer_AccountController extends Mage_Customer_AccountController {

	/**
	 * Create customer account action
	 */
	public function createPostAction() {

		$session = $this->_getSession();
		if ($session->isLoggedIn()) {
			$this->_redirect('*/*/');
			return;
		}
		$session->setEscapeMessages(true); // prevent XSS injection in user input
		if ($this->getRequest()->isPost()) {
			$errors = array();

			if (!$customer = Mage::registry('current_customer')) {
				$customer = Mage::getModel('customer/customer')->setId(null);
			}

			$data = $this->_filterPostData($this->getRequest()->getPost());

			foreach (Mage::getConfig()->getFieldset('customer_account') as $code => $node) {
				if ($node->is('create') && isset($data[$code])) {
					if ($code == 'email') {
						$data[$code] = trim($data[$code]);
					}
					$customer->setData($code, $data[$code]);
				}
			}
			/* contus new */
			if($data['city'] != '')
			{
				$customer->setData('city', $data['city']);
			}


			/**
			 * Initialize customer group id
			 */
			$customer->getGroupId();

			if ($this->getRequest()->getPost('create_address')) {
				$address = Mage::getModel('customer/address')
				->setData($this->getRequest()->getPost())
				->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
				->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false))
				->setId(null);
				$customer->addAddress($address);

				$errors = $address->validate();
				if (!is_array($errors)) {
					$errors = array();
				}
			}

			try {
				$validationCustomer = $customer->validate();
				if (is_array($validationCustomer)) {
					$errors = array_merge($validationCustomer, $errors);
				}
				$validationResult = count($errors) == 0;

				if (true === $validationResult) {
					$customer->save();



					if ($customer->isConfirmationRequired()) {
						$customer->sendNewAccountEmail('confirmation', $session->getBeforeAuthUrl());
						if ($this->getRequest()->getParam('is_subscribed', false)) {
							Mage::getModel('newsletter/subscriber')->subscribe($data['email'], $data['city']);
						}
						$session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));
						$this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure' => true)));
						return;
					} else {
						$session->setCustomerAsLoggedIn($customer);
						$url = $this->_welcomeCustomer($customer);
						if ($this->getRequest()->getParam('is_subscribed', false)) {
							Mage::getModel('newsletter/subscriber')->subscribe($data['email'], $data['city']);
						}
						 
						$successUrl = Mage::getUrl('customer/account', array('_secure' => true));
						$this->_redirectSuccess($successUrl);
					}
				} else {
					$session->setCustomerFormData($this->getRequest()->getPost());
					if (is_array($errors)) {
						foreach ($errors as $errorMessage) {
							$session->addError($errorMessage);
						}
					} else {
						$session->addError($this->__('Invalid customer data'));
					}
				}
			} catch (Mage_Core_Exception $e) {
				$session->setCustomerFormData($this->getRequest()->getPost());
				if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
					$url = Mage::getUrl('customer/account/forgotpassword');
					$message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                    $session->setEscapeMessages(false);
                } else {
                    $message = $e->getMessage();
                }
                $session->addError($message);
            } catch (Exception $e) {
                $session->setCustomerFormData($this->getRequest()->getPost())
                        ->addException($e, $this->__('Cannot save the customer.'));
            }
        }
      
        $this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
    }

}
