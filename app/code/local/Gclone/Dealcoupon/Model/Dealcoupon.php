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
class Gclone_Dealcoupon_Model_Dealcoupon extends Mage_Core_Model_Abstract {
    const XML_PATH_COUPON_TEMPLATE = 'dealcoupon/email/coupon_template';
    const XML_PATH_OWNER_TEMPLATE = 'dealcoupon/email/owner_template';
    const XML_PATH_NO_EMAIL_TEMPLATE = 'dealcoupon/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'dealcoupon/email/sender_email_identity';

    public function _construct() {
        parent::_construct();
        $this->_init('dealcoupon/dealcoupon');
    }

    /*
     * Contus
     * Cron job method to sendcoupons to the buyers
     */

    public function sendCoupon() {
    	$i = 0;
         $date=Mage::getModel('core/date')->date('Y-m-d H:i:s');
        $todayDate = strtotime(Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
       $todayDate = date('m/d/y', $todayDate);
        $_productCollection = Mage::getModel('catalog/product')->getCollection()
                        ->addFieldToFilter(array(
                            array('attribute' => 'Status', 'eq' => '1'),))
                        ->addAttributeToFilter('special_to_date', array('or' => array(
                                0 => array('date' => true, 'from' => $todayDate),
                                1 => array('is' => new Zend_Db_Expr('null')))
                                ), 'left')
                        ->addAttributeToSelect('target_number')
                        ->addAttributeToSelect('price')
                        ->addAttributeToSelect('special_price')
                        ->addAttributeToSelect('special_from_date')
                        ->addAttributeToSelect('special_to_date')
                        ->addAttributeToSelect('price')
                        ->addAttributeToSelect('special_price')
                        ->addAttributeToSelect('name')
                        ->addAttributeToSelect('enjoyby')
                        ->addAttributeToSelect('customersite')
                        ->addAttributeToSelect('fineprint');
                        
                        
        // Sales order collection
        $dealstatus[0] = "processing";
        $dealstatus[1] = "complete";
        $orderItemcollection = Mage::getModel('sales/order_item')
                        ->getCollection();
         $orderCollection = Mage::getModel('sales/order')->getCollection();
        $orderCollection->addAttributeToFilter('status', array('in' => $dealstatus));
        $count = count($_productCollection); //counting the total no of products        
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $resource = Mage::getSingleton('core/resource');
        $currentdeal = $resource->getConnection('core_write');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);
        //currency symbol
        $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();

        //payment status for that product
        $dealstatus[0] = "processing";
        $dealstatus[1] = "complete";
      
        if ($count > 0) {
            //get order customer collection
            $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();
            
            foreach ($_productCollection as $_product) {
                $check = 1;
                 $productId = $_product->getId(); 
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ToDate = strtotime($ProductToDate);
                //checking for Time Expires

                $totalCoupon = $_product->getTargetNumber();

               
                $discount_price = $_product->getPrice() - $_product->getSpecialPrice();
                $discount = ($discount_price * 100) / $_product->getPrice();
                $discount = round($discount);
                $currentdiscount = $discount;
                $Couponvalid = $_product->getEnjoyby();
                if ($Couponvalid != '') {
                    $Couponvalid = date('F j, Y', strtotime($Couponvalid));
                } else {
                    $Couponvalid = 'N/A';
                }
                $Companywebsite = $_product->getCustomer_website();
                $Companyname = $_product->getCustomersite();
                if ($_product->getSpecialPrice()) {
                    $product_worth = $_product->getSpecialPrice();
                } else {
                    $product_worth = $_product->getPrice();
                }
                $currentproductname = $_product->getName();
                $productSku = $_product->getSku();
                $product_description = $_product->getDescription();
                $fineprint = $_product->getFineprint();
                $total_orders = "0";

                //$orderItemcollection->addAttributeToFilter('product_id', $productId);
                //gift message details
               $resultGiftMessage = array();
                $select = $read->select()
                    ->from(array('p' => $resource->getTableName('gift_message')), new Zend_Db_Expr('gift_message_id,customer_id,sender,recipient,message,email,product_id,order_id,type'))
                    ->where('p.product_id = ?',$productId);
                $giftMessage = $read->fetchAll($select);

                foreach ($giftMessage as $value) {
                    $resultGiftMessage[$value['order_id']] = $value;
                }
                $quantity = array();
                $quantity[0] = "0";
                $cmail = array();
                foreach ($orderCollection as $_order) {
                    $order_id = $_order->getEntityId();
                    foreach ($orderItemcollection as $item) {
                        $_orderId = $item->getOrderId();
                        $sku = $item->getSku();
                        if ($order_id == $_orderId) {
                            $orderId = $_order->getIncrementId();
                            $qty = array();
                            $orderCustUniqueId = Mage::getModel('ordercustomer/ordercustomer')->getCouponData($orderCustCollection, $order_id);
                            if ($productId == $item->getProductId()) {
                                $total_orders = $total_orders + 1;
                                $qty[0] = $item->getQtyOrdered();
                                if (empty($orderCustUniqueId)) {
                                    $cmail[$i][1] = $qty[0];
                                    $cmail[$i][3] = Mage::getBaseUrl() . 'sales/order/print/order_id/' . $order_id . '/';
                                    $customerId = $_order->getCustomerId();
                                    if (isset($resultGiftMessage[$order_id]['email']) && $resultGiftMessage[$order_id]['type'] == 1) {
                                        $cmail[$i][0] = $resultGiftMessage[$order_id]['email'];
                                        $cmail[$i][2] = $resultGiftMessage[$order_id]['recipient'];
                                            $cmail[$i][10] = $resultGiftMessage[$order_id]['message'];
                                $cmail[$i][11] = $resultGiftMessage[$order_id]['sender'] . ' has sent you a Gift.';
                                    } else {
                                        $cmail[$i][0] = $_order->getCustomerEmail();
                                        $cmail[$i][2] = $_order->getCustomerFirstname() . ' ' . $_order->getCustomerLastname();
                                    }
                                    $cmail[$i][4] = $_order->getGrandTotal();
                                    $cmail[$i][6] = $order_id;
                                    $cmail[$i][7] = $item->getQtyOrdered();
                                    $cmail[$i][8] = $orderId;
                                    $i++;
                                }
                                $quantity[0] = $quantity[0] + $qty[0];
                            }
                            // }
                        }
                    }
                }
                /* for custom options of a product */
                if (count($orderCollection) > 0) {
                    $productId = $_product->getId();
                    $i = 0;
                    $emailto = "";
                    if (count($cmail)) {
                        foreach ($cmail as $cmail1) {
                            $j = 1;
                            $customer_name = $cmail1[2];
                            $customerName = str_replace("'", "", $customer_name);
                            $tocustomer = $cmail1[0];
                            $orderId = $cmail1[6];

                            $product = Mage::getModel('catalog/product')->load($productId);
                            $attVal = $product->getOptions();
                            
                            if (sizeof($attVal)) {
                                foreach ($attVal as $optionVal) {
                                    $customTitle = $optionVal->getTitle();
                                    foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
                                        $sku = $valuesVal->getId();
                                        $dealstatus[0] = "processing";
                                        $dealstatus[2] = "complete";
                                        $collection = Mage::getModel('sales/order')->getCollection()
                                                        ->addFieldToFilter('status', array('in' => $dealstatus));
                                        $collection->join('sales/order_item', '`sales/order_item`.order_id=`main_table`.entity_id',
                                                        array('proptions' => new Zend_Db_Expr('group_concat(`sales/order_item`.product_options SEPARATOR ",")'),)
                                                )
                                                ->addAttributeToFilter('order_id', array('eq' => $orderId));
                                        $collection->getSelect()->group('entity_id');
                                        foreach ($collection as $item) {
                                            $value = $item->getProptions();
                                            $value = unserialize($value);
                                            $option_array = $value['options'];
                                            $options = "";
                                            if (count($option_array) > 0) {
                                                foreach ($option_array as $_option) {
                                                    if ($_option['option_value'] == $sku) {
                                                        $customoptionTitle = $valuesVal->getTitle();
                                                        if ($_product->getSpecialPrice()) {
                                                            $customPrice = $_product->getSpecialPrice();
                                                        } else {
                                                            $customPrice = $_product->getPrice();
                                                        }
                                                        if ($valuesVal->getPriceType() == 'fixed') {
                                                            $customPrice = $customPrice + $valuesVal->getPrice();
                                                        } else {
                                                            $percent = $customPrice * ($valuesVal->getPrice() / 100);
                                                            $customPrice = $customPrice + $percent;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if ($customPrice) {
                                $product_worth = $customPrice;
                            }

                            //Deal Not Achieved
                            if ($quantity[0] >= $totalCoupon) {//Time Expiers
                            //Deal Achieved
                           
                                $achieved = "yes";
                                $inserting = "";
                                $orderid = $cmail1[6];
                                $orderedqty = floor($cmail1[7]);
                                $message = $cmail1[11] . '<br>' . $cmail1[10] . '<br>';
                                $subName = $cmail1[11];
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
                                        $uniqueImage .= '<br>'.'<img src=' . $path . '>';
                                    

                                    //Barcode Generation ends
                                    $postObject = new Varien_Object();
                                    if ($subName == '') {
                                        $subjectname = 'Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title') . ' Order Quantity No:' . $i;
                                    } else {
                                        $subjectname = $subName;
                                     }
                                    $product_worth=number_format($product_worth,2,'.','');
                                    $discount=number_format($discount,2,'.','');
                                    $postObject->setData(array('total' => $cmail1[4], 'realorderid' => $cmail1[8], 'product_description' => $product_description, 'customer_name' => $customer_name, 'productname' => $currentproductname, 'couponcode' => $uniqueImage, 'discount' => $discount, 'couponvalid' => $Couponvalid, 'companywebsite' => $Companywebsite, 'fineprint' => $fineprint, 'company_address' => $Companyname, 'currency_symbol' => $currencySymbol, 'product_worth' => $product_worth, 'quantity' => $orderedqty, 'message' => $message, 'subjectname' => $subjectname, 'customtitle' => $customTitle, 'customoptiontitle' => $customoptionTitle));
                                    
                                    $mailTemplate = Mage::getModel('core/email_template');
                                    $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                                    $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                                    $mailTemplate->setRecipientName('');
                                    $mailTemplate->setTemplateSubject('Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title'));


                                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                            ->sendTransactional(
                                                    Mage::getStoreConfig(self::XML_PATH_COUPON_TEMPLATE),
                                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                                    $tocustomer,
                                                    $customerName,
                                                    array('deallist' => $postObject)
                                    );

                                    if ($mailTemplate->getSentSuccess()) {
$customTitle_insert = '';
                                        if ($inserting == "Yes") {
                                        	if($customTitle!= '')
                            	{
                            	$customTitle_insert = '-Custom Title:'.$customTitle.'-Custom Option Title:'.$customoptionTitle;
                            	}
                                            $date=Mage::getModel('core/date')->date('Y-m-d H:i:s');
                                           $insertCoupon .= "($orderid,'" . $customerName . "','" . addslashes($currentproductname).addslashes($customTitle_insert) . "','" . $orderedqty . "','" . $uniquenumber . "'," . $i . ",'".  $date ."', '1')";
                                        }
                                        $cronCouponSend[] = "success";
                                    } else {
                                        $cronCouponSend[] = "failed";
                                    }
                                }
                            }//deal Achieved
                            $j = $j + 1;
                        }
                    }
                 
                    if (in_array('success', $cronCouponSend)) {
                        $dealOwner = $_product->getcustomeremail();
                        //sending e-mail to DealOwner
                        if ($achieved == "no") {

                            $postObject = new Varien_Object();

                            $storeName = Mage::getStoreConfig("design/head/default_title");

                            $postObject->setData(array('storename' => $storeName, 'productname' => $currentproductname, 'purchasedqty' => $quantity[0], 'quantity' => $totalCoupon));
                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                            $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                            $mailTemplate->setTemplateSubject(Mage::getStoreConfig('design/head/default_title') . ": Today's deal is not achieved!!");

                            /* @var $mailTemplate Mage_Core_Model_Email_Template */
                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                    ->sendTransactional(
                                            Mage::getStoreConfig(self::XML_PATH_NO_EMAIL_TEMPLATE),
                                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                            $dealOwner,
                                            Mage::getStoreConfig('design/head/default_title'),
                                            array('deallist' => $postObject)
                            );
                        } else {
                            $customerdetails = $cmail;
                            $uniquelist = "";
                            $Ownertemplate = "";
                            $Ownertemplate .= '
                            <table border="1">
                              <tr>
                                <th>Order ID</th>
                                <th>Customer name</th>
                                <th>Coupon Code</th>
                                <th>Deal Name</th>
                                <th>No of coupons purchased</th>
                                <th>Customer Email</th>
                              </tr>
                              ';
                            $uniqeid = $tprefix . "ordercustomer";
                            if (!empty($customerdetails)) {
                                foreach ($customerdetails as $customerdetails1) {
                                    $select = $read->select()
                                                    ->from(array('cp' => $uniqeid))
                                                    ->where('cp.order_id=?', $customerdetails1[6]);

                                    $uniquelist = $read->fetchAll($select);
                                    $k = 0;
                                    foreach ($uniquelist as $uniqueCuslist) {
                                        if ($k == 0) {
                                            $k = 1;
                                        } else {
                                            $uniqueCuslist['quantity'] = ' ';
                                        }
                                        $Ownertemplate .= '
                                  <tr>
                                    <td align="center">' . $customerdetails1[8] . '</td>
                                    <td>' . $uniqueCuslist['customer_name'] . '</td>
                                    <td align="center">' . $uniqueCuslist['uniqueid'] . '</td>
                                    <td>' . $uniqueCuslist['product_name'] . '</td>
                                    <td align="center">' . $uniqueCuslist['quantity'] . '</td>
                                    <td>' . $customerdetails1[0] . '</td>
                                  </tr>
                                  ';
                                    }
                                }
                            }

                            $Ownertemplate .= '
</table>
';
                            $emailto = "";

                            $myFile = Mage::getBaseDir('export') . "/DealList.csv";
                            $fh = fopen($myFile, 'w') or die("can't open file");
                            $stringData = $this->CSVFile($productId);
                            fwrite($fh, $stringData);
                            fclose($fh);

                            $post['content'] = $Ownertemplate;
                            $emailTemplateVariables = array();
                            $postObject = new Varien_Object();

                            $postObject->setData(array('content' => $post['content']));

                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                            $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                            $mailTemplate->setTemplateSubject('Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title'));

                            $fileContents = file_get_contents(Mage::getBaseDir('export') . "/DealList.csv"); /* (Here put the filename with full path of file, which have to be send) */
                            $attachment = $mailTemplate->getMail()->createAttachment($fileContents);
                            $attachment->filename = 'DealList.csv';

                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                    ->sendTransactional(
                                            Mage::getStoreConfig(self::XML_PATH_OWNER_TEMPLATE),
                                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                            $dealOwner,
                                            Mage::getStoreConfig('design/head/default_title'),
                                            array('deallist' => $postObject));
                        }

                        $resultCoupon .= "('','" . $productId . "','1','" . $achieved . "')";
                        if ($check != $count) {
                            $resultCoupon .= ',';
                        }
                    }
                }
                // } //already send
                $check = $check + 1;
            }//checking date
            if (isset($insertCoupon)) {
                $insertCoupon = str_ireplace("'1')", "'1'),", $insertCoupon);
                $str = $insertCoupon;
                $length = strlen($str);
                $insertCoupon = substr("$str", 0, $length - 1);

                $currentdeal->query("insert into " . $tprefix . "ordercustomer (order_id,customer_name,product_name,quantity,uniqueid,quantitynumber,created_time,couponstatus ) values " . $insertCoupon);
                //$currentdeal->query("insert into " . $tprefix . "ordercustomer (order_id,customer_name,product_name,quantity,uniqueid,quantitynumber,created_time,couponstatus ) values " . $insertCoupon);
            }
            if (isset($resultCoupon)) {
                $str = $resultCoupon;
                $length = strlen($str);
                $resultCoupon = substr("$str", 0, $length - 1);
                $currentdeal->query("INSERT INTO " . $tprefix . "cron_send_coupon VALUES " . $resultCoupon);
              
            }
        }//foreach
        
        exit;
    }

//function sendCoupon

    /* CSV Format File */

    public function CSVFile($productId) {
        $product = Mage::getModel('catalog/product')->load($productId);
        $resource = Mage::getSingleton('core/resource');
        $orderTable = $resource->getTableName('sales/order');
        $read = $resource->getConnection('read');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        //checking if the coupons send for that particular product
        $flatorderTable = $tprefix . "sales_flat_order_item";
        $total_orders = "0";

        $dealStatus[0] = "processing";
        $dealStatus[1] = "complete";
        $select = $read->select()
                        ->from(array('cp' => $orderTable))
                        ->join(array('pei' => $flatorderTable), 'pei.order_id=cp.entity_id', array())
                        ->where('pei.product_id=?', $productId)
                        ->where('cp.status in (?)', $dealStatus);

        $orders_list = $read->fetchAll($select);
        $ordersListCount = count($orders_list);

        //gift message details
        $resultGiftMessage = array();

        $select = $read->select()
                    ->from(array('p' => $resource->getTableName('gift_message')), new Zend_Db_Expr('gift_message_id,customer_id,sender,recipient,message,email,product_id,order_id,type'))
                    ->where('p.product_id = ?',$productId);
        $giftMessage = $read->fetchAll($select);  
        foreach ($giftMessage as $value) {
            $resultGiftMessage[$value['order_id']] = $value;
        }
        //get order customer collection
        $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

        if ($ordersListCount > 0) {
            $quantity = array();
            $quantity[0] = "0";
            $i = 0;
            $customerdetails = array();
            foreach ($orders_list as $rows) {
                $order_id = $rows['entity_id'];
                $orderId = $rows['increment_id'];
                $order = Mage::getModel('sales/order')->load($order_id);
                $items = $order->getAllItems();
                $qty = array();
                foreach ($items as $itemId => $item) {
                    if ($productId == $item->getProductId()) {
                        $total_orders = $total_orders + 1;
                        $qty[0] = $item->getQtyOrdered();
                        $customerdetails[$i][1] = $qty[0];
                        $customerdetails[$i][3] = Mage::getBaseUrl() . 'sales/order/print/order_id/' . $order_id . '/';
                        $customerId = $order->getCustomerId();
                        //$resultcustomerid = $read->fetchRow("Select email,recipient  from " . $tprefix . "gift_message  where customer_id = '$customerId'");
                        if (isset($resultGiftMessage[$order_id]['email'])) {
                            $cmail[$i][0] = $resultGiftMessage[$order_id]['email'];
                            $cmail[$i][2] = $resultGiftMessage[$order_id]['recipient'];
                        } else {
                            $customerdetails[$i][0] = $order->getCustomerEmail();
                            $customerdetails[$i][2] = $order->getCustomerName();
                        }
                        $customerdetails[$i][4] = $order->getGrandTotal();
                        $customerdetails[$i][6] = $order_id;
                        $customerdetails[$i][7] = $item->getQtyOrdered();
                        $customerdetails[$i][8] = $orderId;
                        $quantity[0] = $quantity[0] + $qty[0];
                        $i++;
                    }
                }
            }
            $uniquelist = "";
            $template = "";
            $template .= '"Order ID","Customer name","Coupon Code","Deal Name","coupons purchased","Customer Email"' . "\n";
            $uniqeid = $tprefix . "ordercustomer";
            if (!empty($customerdetails)) {
                foreach ($customerdetails as $customerdetails1) {

                    $orderCustData = Mage::getModel('ordercustomer/ordercustomer')->getCouponData($orderCustCollection, $customerdetails1[6], NULL, 'all');

                    $k = 0;
                    foreach ($orderCustData as $uniqueCuslist) {
                        if ($k == 0) {
                            $k = 1;
                        } else {
                            $quantity = ' ';
                        }
                        $uniqueId = $uniqueCuslist->getUniqueid();
                        $customerName = $uniqueCuslist->getCustomerName();
                        $productName = $uniqueCuslist->getProductName();
                        $quantity = $uniqueCuslist->getQuantity();

                        $template .= '"' . $customerdetails1[8] . '","' . $customerName . '","' . $uniqueId . '","' . $productName . '","' . $quantity . '","' . $customerdetails1[8] . '","' . $customerdetails1[0] . '"' . "\n";
                    }
                }
            }
        } else {
            $template = '';
        }
        return $template;
    }

}