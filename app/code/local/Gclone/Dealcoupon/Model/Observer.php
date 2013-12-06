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
class Gclone_Dealcoupon_Model_Observer {

	const XML_PATH_COUPON_TEMPLATE = 'dealcoupon/email/coupon_template';
	const XML_PATH_OWNER_TEMPLATE = 'dealcoupon/email/owner_template';
	const XML_PATH_NO_EMAIL_TEMPLATE = 'dealcoupon/email/email_template';
	const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
	const XML_PATH_EMAIL_SENDER = 'dealcoupon/email/sender_email_identity';





	public function sendCouponProduct($observer)
	{


		$currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		$order = $observer->getEvent()->getOrder();
		$order_id = $order->getId();
		$orderid = $order->getIncrementId();
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('catalog_read');
		$currentdeal = $resource->getConnection('core_write');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$orderTable = $resource->getTableName('sales/order');
		$orderItemTable = $resource->getTableName('sales/order_item');
		$tprefix = (string) Mage::getConfig()->getTablePrefix();
		$siteTurnOverTable = $tprefix . "site_turnover";
                $conn = $resource->getConnection('core_read');
		//check whethe the order id already exists or not in ordercustomer table
		/** @var $select Varien_Db_Select */
		$select = $read->select()
		->from(array('p' => $resource->getTableName('ordercustomer')), new Zend_Db_Expr('COUNT(ordercustomer_id)'))
		->where('p.order_id = ?',$orderid );

		$order_id_exist = $read->fetchOne($select);
                $orderdetails = Mage::getModel('sales/order')->loadByIncrementId($orderid);


		if($order_id_exist == '0' && ($orderdetails->getStatus() == 'processing' || $orderdetails->getStatus() == 'complete' ))
		{

			$orderdetails = Mage::getModel('sales/order')->loadByIncrementId($orderid);
			$orders = $orderdetails->getAllItems();
			$tocustomer = $orderdetails->getCustomerEmail();
			$customer_name = $orderdetails->getCustomerFirstname(). ' '. $orderdetails->getCustomerLastname();
			foreach ($orders as $itemId => $item)
			{
					
				$productId =$item->getProductId();
				$product_total_ordered = $item->getQtyOrdered();
				$product_details = Mage::getModel('catalog/product')->load($productId);
				$options = $item->getProductOptions();
				if($product_details->hasOptions()) {
				$customOptions = $options['options'];
				if(!empty($customOptions))
				{
					foreach ($customOptions as $option)
					{
						$customTitle = $option['label'];
						$customoptionTitle = $option['value'];
					}

				}
			}
			}

			
			
			if($product_details->getTargetNumber() == '' || $product_details->getTargetNumber() == '0' )
			{
				$productFinePrint = $product_details->getFineprint();
				$Companyname = $product_details->getCustomersite();
				$Companywebsite = $product_details->getCustomerWebsite();
				$product_description = $product_details->getDescription();
				$expireson = $product_details->getSpecialToDate();
				$Couponvalid = $product_details->getEnjoyby();
				$currentproductname = $product_details->getName();
				if ($Couponvalid != '') {
					$Couponvalid = date('F j, Y', strtotime($Couponvalid));
				} else {
					$Couponvalid = 'N/A';
				}

				$discount_price = $product_details->getPrice() - $product_details->getSpecialPrice();
				$discount = ($discount_price * 100) / $product_details->getPrice();

				$product_worth = $product_details->getspecialPrice();
					
					
					
					




				//get product barcode image
				$orderedqty = floor($product_total_ordered);
				for ($i = 1; $i <= $orderedqty; $i++) {
					$random_chars = "";
					$characters = array(
                                        "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
                                        "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                                        "1", "2", "3", "4", "5", "6", "7", "8", "9");

					$keys = array();
					while (count($keys) < 9) {
						$x = mt_rand(0, count($characters) - 1);
						if (!in_array($x, $keys)) {
							$keys[] = $x;
						}
					}

					foreach ($keys as $key) {
						$random_chars .= $characters[$key];
					}

					$uniquenumber = $random_chars;
					$inserting = "Yes";
					//                                    }
					//Barcode Generation starts
					$barcodeFileName = $uniquenumber;
					$uniqueImage = $uniquenumber;

					$obj = Gclone_Barcode_Block_Barcode::generateBarcode($uniquenumber, $barcodeFileName);
					$path = str_replace('index.php/', '',
					Mage::getBaseUrl()) . '/media/barcode/' . $barcodeFileName . '.png';
					$uniqueImage .= '<img src=' . $path . '>';



					$resultGiftMessage = array();
					$select = $read->select()
					->from(array('p' => $resource->getTableName('gift_message')), new Zend_Db_Expr('gift_message_id,customer_id,sender,recipient,message,email,product_id,order_id,type'))
					->where('p.order_id	 = ?',$order_id);
					$giftMessage = $read->fetchAll($select);
						
					foreach ($giftMessage as $value) {
						$resultGiftMessage[$value['order_id']] = $value;
					}
						
						
					if (isset($resultGiftMessage[$order_id]['email'])) {
						$tocustomer = $resultGiftMessage[$order_id]['email'];
						$customer_name = $resultGiftMessage[$order_id]['recipient'];
						$message = $resultGiftMessage[$order_id]['message'];
					}

					//Barcode Generation ends
					$postObject = new Varien_Object();
					$subjectname = 'Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title') . ' Order Quantity No:' . $i;
					

					$mailTemplate = Mage::getModel('core/email_template');
					$mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
					$mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
					$mailTemplate->setRecipientName('');
					$mailTemplate->setTemplateSubject('Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title'));
					$postObject->setData(array('total' =>$product_total_ordered , 'realorderid' => $orderid, 'product_description' => $product_description, 'customer_name' => $customer_name, 'productname' => $currentproductname, 'couponcode' => $uniqueImage, 'discount' => $discount, 'couponvalid' => $Couponvalid, 'companywebsite' => $Companywebsite, 'fineprint' => $productFinePrint, 'company_address' => $Companyname, 'currency_symbol' => $currencySymbol, 'product_worth' => $product_worth, 'quantity' =>$product_total_ordered , 'message' => $message, 'subjectname' => $subjectname, 'customtitle' => $customTitle, 'customoptiontitle' => $customoptionTitle));
					/* @var $mailTemplate Mage_Core_Model_Email_Template */
					$mailTemplate->setDesignConfig(array('area' => 'frontend'))
					->sendTransactional(
					Mage::getStoreConfig(self::XML_PATH_COUPON_TEMPLATE),
					Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
					$tocustomer,
					$customer_name,
					array('deallist' => $postObject)
					);

					 

		  if ($mailTemplate->getSentSuccess()) {
                                      
                                       $insert = $currentdeal->insert(
                                               $resource->getTableName('ordercustomer'),
                                               array('order_id' => $order_id, 'customer_name' => $customer_name, 'quantity'=> $i,'quantitynumber' => $i, 'product_name' => addslashes($currentproductname), 'uniqueid' => $uniquenumber, 'created_time' => now(), 'couponstatus' => '1')
                                            );
                                          
                                     
                                    }   
		}
        }

	}
	}
}