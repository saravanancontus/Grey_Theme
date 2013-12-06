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

#include 'Mage/Adminhtml/controllers/Newsletter/SubscriberController.php';

class Gclone_Constantcontact_IndexController extends Mage_Adminhtml_Controller_Action# Mage_Adminhtml_Newsletter_SubscriberController
{

	public function indexAction() {

		#collect all subscribers users
		$collectionarray = Mage::getResourceModel('newsletter/subscriber_collection')
										->showStoreInfo()
										->showCustomerInfo()
										->useOnlySubscribed()
										->toArray();

		if ( $collectionarray['totalRecords'] > 0 ) {
			#make the call
			Mage::getSingleton('constantcontact/constantcontact')->batchSubscribe($collectionarray['items']);
		}

		$this->_redirect('adminhtml/newsletter_subscriber/');
	}

}

?>
