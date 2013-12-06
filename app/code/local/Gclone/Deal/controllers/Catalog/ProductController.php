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
require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';

class Gclone_Deal_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController {

    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected $_publicActions = array('edit');
    const XML_PATH_COUPON_TEMPLATE = 'dealcoupon/email/coupon_template';
    const XML_PATH_OWNER_TEMPLATE = 'dealcoupon/email/owner_template';
    const XML_PATH_NO_EMAIL_TEMPLATE = 'dealcoupon/email/email_template';
    const MERCHANT_PATH_ADDDEAL_TEMPLATE = 'merchant/email/adddeal_template';
    //const XML_PATH_EMAIL_SENDER     = 'catalog/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'catalog/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'dealcoupon/email/sender_email_identity';


    /*
     * Contus
     * Merchant Deal Activation action
     */

    public function activationAction() {
    	 $previous_url = $_SERVER['HTTP_REFERER']; 
        $model = Mage::getModel('catalog/product'); //getting product model
        $dealId = $this->getRequest()->getParam('id');
        $row = $model->load($dealId);
        $dealName = $row->getName();
        $amount = $row->getSpecialPrice();
        $merchant = $row->getMerchant();
        $userId = Mage::getSingleton('admin/session')->getUser()->getId();
        $userEmail = Mage::getSingleton('admin/session')->getUser()->getEmail();
        $paymentStatus = 'Completed';
        $adminApproved = 'yes';
        $date = date("Y-m-d", time());

        $resource1 = Mage::getSingleton('core/resource');
        $dealPayment = $resource1->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $productStatus = $dealPayment->fetchRow("Select product_id  from " . $tprefix . "deal_payment where product_id = " . $dealId);
        $count = count($productStatus['product_id']);
        if ($count >= 0) {
            $insert = $dealPayment->insert($resource1->getTableName('deal_payment'),
                 array('payer_id' => $userId, 'payer_email' => $userEmail, 'product_id' => $dealId, 'amount' => $amount, 'date' => $date, 'payment_status' => $paymentStatus, 'admin_approved' => $adminApproved)
            );
            
            $productCollection = Mage::getModel('catalog/product')->load($dealId);
            $productCollection->setStatus(1)
                              ->save();
                              
                              
            
            
            
        }
        
        
        $spilts = explode('/',$previous_url);
        
        if(in_array('adddeals', $spilts))
        {
        	
        	$this->getResponse()->setRedirect($previous_url);
        	
        }else{
        
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
        }
    }

    /*
     * Contus
     * Admin Approve action
     */

    public function approveAction() {
    	 $previous_url = $_SERVER['HTTP_REFERER']; 
        $dealId = $this->getRequest()->getParam('id');
        $resource1 = Mage::getSingleton('core/resource');
        $dealApprove = $resource1->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $dealApprove->update($resource1->getTableName('deal_payment'),
               array('admin_approved' => 'yes'),
               array('product_id = ?' => $dealId,)
        );
        
        $productCollection = Mage::getModel('catalog/product')->load($dealId);
            $productCollection->setStatus(1)
                              ->save();
        
        
        
    $spilts = explode('/',$previous_url);
        
        if(in_array('adddeals', $spilts))
        {
        	
        	$this->getResponse()->setRedirect($previous_url);
        	
        }else{
        
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
        }
    }

    /** Contus
     * Send email to Deal Owner
     */
    public function sendemailAction() {

        $productId = (int) $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productId);
        $current_product = $product->getId();
        $currentproductname = $product->getName();
        $customeremail = $product->getcustomeremail();
        $totalcoupon = $product->getTargetNumber();
        $resource = Mage::getSingleton('core/resource');
        $orderTable = $resource->getTableName('sales/order');
        $read = $resource->getConnection('read');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $flatorderTable = $tprefix . "sales_flat_order_item";
        $total_orders = "0";
        $dealStatus[1] = "processing";
        $dealStatus[0] = "complete";

        $orderItemcollection = Mage::getModel('sales/order_item')
                        ->getCollection();
        $orderCollection = Mage::getModel('sales/order')
                        ->getCollection()->addAttributeToFilter('status', array('in' => $dealStatus));


        $totalQtyPurchase = 0;
        $orderItemcollection->addAttributeToFilter('product_id', $productId);

        foreach ($orderItemcollection as $item) {
            $orderItemId = $item->getOrderId();
            foreach ($orderCollection as $_order) {
                $orderId = $_order->getEntityId();
                if ($orderId == $orderItemId) {
                    $totalCount += $_order->getTotalQtyOrdered();
                }
            }
        }
        $totalQtyPurchase = $totalCount;

        if ($totalQtyPurchase < $totalcoupon) {

            $post['content'] = $template;
            $postObject = new Varien_Object();
            $storeName = Mage::getStoreConfig("design/head/default_title");
            $postObject->setData(array('storename' => $storeName, 'purchasedqty' => $totalQtyPurchase, 'productname' => $currentproductname, 'quantity' => $totalcoupon));
            $mailTemplate = Mage::getModel('core/email_template');
            $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
            $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
            $mailTemplate->setTemplateSubject(Mage::getStoreConfig('design/head/default_title') . ": Today's deal is not achieved!!");

            /* @var $mailTemplate Mage_Core_Model_Email_Template */
            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                            Mage::getStoreConfig(self::XML_PATH_NO_EMAIL_TEMPLATE),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                            $customeremail,
                            Mage::getStoreConfig('design/head/default_title'),
                            array('deallist' => $postObject)
            );

            if (!$mailTemplate->getSentSuccess()) {
                $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
                $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
                return;
            }

            $this->_getSession()->addSuccess('Deal List Sent to Owner.');
            $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
            return;
        } else {
            //get order customer collection
            $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

            $quantity = array();
            $quantity[0] = "0";
            $i = 0;
            $customerdetails = array();
            //gift message details
            $resultGiftMessage = array();
            $select = $read->select()
                    ->from(array('p' => $resource->getTableName('gift_message')), new Zend_Db_Expr('gift_message_id,customer_id,sender,recipient,message,email,product_id,order_id,type'))
                    ->where('p.product_id = ?',$productId);
            $giftMessage = $read->fetchAll($select);
           
            $giftMessage = $read->fetchAll("Select *  from " . $tprefix . "gift_message  where product_id ='$productId'");
           
            foreach ($giftMessage as $value) {
                $resultGiftMessage[$value['order_id']] = $value;
            }

            foreach ($orderCollection as $_order) {
                $order_id = $_order->getEntityId();
                foreach ($orderItemcollection as $item) {
                    $_orderId = $item->getOrderId();
                    if ($order_id == $_orderId) {
                        $orderId = $_order->getIncrementId();
                        $qty = array();
                        if ($productId == $item->getProductId()) {
                            $total_orders = $total_orders + 1;
                            $qty[0] = $item->getQtyOrdered();

                            $cmail[$i][1] = $qty[0];
                            $cmail[$i][3] = Mage::getBaseUrl() . 'sales/order/print/order_id/' . $order_id . '/';
                            $customerId = $_order->getCustomerId();

                            if (isset($resultGiftMessage[$order_id]['email']) && $resultGiftMessage[$order_id]['type'] == 1) {
                                $cmail[$i][0] = $resultGiftMessage[$order_id]['email'];
                                $cmail[$i][2] = $resultGiftMessage[$order_id]['recipient'];
                            } else {
                                $cmail[$i][0] = $_order->getCustomerEmail();
                                $cmail[$i][2] = $_order->getCustomerFirstname() . ' ' . $_order->getCustomerLastname();
                            }
                            $cmail[$i][4] = $_order->getGrandTotal();
                            $cmail[$i][6] = $order_id;
                            $cmail[$i][7] = $item->getQtyOrdered();
                            $cmail[$i][8] = $orderId;
                            $i++;

                            $quantity[0] = $quantity[0] + $qty[0];
                        }
                    }
                }
            }
            $customerdetails = $cmail;
            $uniquelist = "";
            $template = "";
            $template .= '
            <table border="0" cellpadding="4" cellspacing="0" border="0" style="border: 1px solid #8bba3f; border-bottom:0; width: 100%; color: #000 !important;">
              <tr style=background: #ebfccf;>
                <th style="border-bottom: 1px solid #8bba3f; padding: 5px; width: 10%; text-align:left;" >Order ID</th>
                <th style="border-bottom: 1px solid #8bba3f;  width: 12%; text-align:left;">Customer name</th>
                <th style="border-bottom: 1px solid #8bba3f; width: 12%; text-align:left;">Coupon Code</th>
                <th style="border-bottom: 1px solid #8bba3f;  width: 30%; text-align:left;">Deal Name</th>
                <th style="border-bottom: 1px solid #8bba3f;  width: 20%; text-align:left;">No of coupons purchased</th>
                <th style="border-bottom: 1px solid #8bba3f; text-align:left;">Customer Email</th>
              </tr>
              ';
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

                        $template .= '
                          <tr>
                            <td style="border-bottom: 1px solid #8bba3f; padding:5px; text-align:left;">' . $customerdetails1[8] . '</td>
                            <td style="border-bottom: 1px solid #8bba3f; text-align:left;">' . $customerName . '</td>
                            <td style="border-bottom: 1px solid #8bba3f; text-align:left;">' . $uniqueId . '</td>
                            <td style="border-bottom: 1px solid #8bba3f; text-align:left;">' . $productName . '</td>
                            <td style="border-bottom: 1px solid #8bba3f; text-align:center;">' . $quantity . '</td>
                            <td style="border-bottom: 1px solid #8bba3f; text-align:left;">' . $customerdetails1[0] . '</td>
                          </tr>
                          ';
                    }
                }
            }

            $template .= '</table>';
            $emailto = "";
            $myFile = Mage::getBaseDir('media') . "/New.csv";
            $fh = fopen($myFile, 'w') or die("can't open file");
            $stringData = $this->CSVFile();
            fwrite($fh, $stringData);
            fclose($fh);
            $dest = Mage::getBaseDir('export') . "/DealList.csv";
            copy($myFile, $dest);
            unlink($myFile);
            $post['content'] = $template;
            $emailTemplateVariables = array();
            $postObject = new Varien_Object();
            $postObject->setData(array('content' => $post['content']));

            if (empty($customerdetails)) {
                $this->_getSession()->addError("No orders Found for this Deal !");
            } elseif (empty($orderCustData)) {
                $this->_getSession()->addError("Please Send Coupons to Customers of product Id : $productId and then try it!!!");
            } else {
                $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                $mailTemplate->setTemplateSubject('Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title'));

                $fileContents = file_get_contents(Mage::getBaseDir('export') . "/DealList.csv"); /* (Here put the filename with full path of file, which have to be send) */
                $attachment = $mailTemplate->getMail()->createAttachment($fileContents);
                $attachment->filename = 'DealList.csv';

                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                        ->sendTransactional(
                                Mage::getStoreConfig(self::XML_PATH_OWNER_TEMPLATE),
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                $customeremail,
                                Mage::getStoreConfig('design/head/default_title'),
                                array('deallist' => $postObject)
                );

                if (!$mailTemplate->getSentSuccess()) {
                    $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
                    $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
                    return;
                }

                $this->_getSession()->addSuccess('Deal List Sent to Owner.');
                $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
    }

    /* download deal list */

    public function downloadAction() {
        $template = $this->CSVFile();
        if ($template != '') {
            $fileName = 'dealList.csv';
            $content = $template;
            $this->_prepareDownloadResponse($fileName, $content);
        } else {
            $this->_getSession()->addError("No orders Found for that Deal !");
            $this->getResponse()->setRedirect($this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store'))));
        }
    }

    /* download deal list */

     public function couponAction() {
        $productId = $this->getRequest()->getParam('id');
        $storeId = $this->getRequest()->getParam('store');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $model = Mage::getModel('catalog/product'); //getting product model
        $productdetail = $model->load($productId);
        $data = $this->getRequest()->getPost();
        $current_product = $productdetail->getId();
        $currentproductname = $productdetail->getName();
        $totalcoupon = $productdetail->getTargetNumber();
         $attVal = $productdetail->getOptions();


        //$currentproductimage = Mage::helper('catalog/image')->init($productdetail, 'image')->resize(386, 338);

        //currency symbol
        $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();

        $discount_price = $productdetail->getPrice() - $productdetail->getSpecialPrice();
        $discount = ($discount_price * 100) / $productdetail->getPrice();
        $discount = round($discount);
        $currentdiscount = $discount;
        $Couponvalid = $productdetail->getEnjoyby();
        if($Couponvalid != ''){
        $Couponvalid = date('F j, Y', strtotime($Couponvalid));
        }else{
        $Couponvalid = 'N/A';
        }
        $Companywebsite = $productdetail->getCustomer_website();
        $Companyname = $productdetail->getCustomersite();
        $product_worth = $productdetail->getSpecialPrice();
        if ($customPrice) {
             $product_worth = $product_worth + $customPrice;
        }
       $product_worth = number_format($product_worth, 2, '.', '');
        $product_description = $productdetail->getDescription();
        $fineprint = $productdetail->getFineprint();

        $resource = Mage::getSingleton('core/resource');
        $giftemail = $resource->getConnection('core_write');

        // Sales order collection
        $dealstatus[0] = "processing";
        $dealstatus[1] = "complete";
        $orderItemcollection = Mage::getModel('sales/order_item')
                        ->getCollection();
        $orderCollection = Mage::getModel('sales/order')
                        ->getCollection()->addAttributeToFilter('status', array('in' => $dealstatus));


        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        $total_orders = "0";
        $read = $resource->getConnection('catalog_read');

        $fetch_list = array();
        $quantity = array();
        $quantity[0] = "0";
        $cmail = array();

        if (count($orderCollection) > 0) {
            $i = 0;
            //gift message details
            $resultGiftMessage = array();
            $giftMessage = $read->fetchAll("Select *  from " . $tprefix . "gift_message  where product_id ='$productId'");
            foreach ($giftMessage as $value) {
                $resultGiftMessage[$value['order_id']] = $value;
            }

            foreach ($orderCollection as $_order) {
                $order_id = $_order->getEntityId();
                foreach ($orderItemcollection as $item) {
                    $_orderId = $item->getOrderId();
                    if ($order_id == $_orderId) {
                        $orderId = $_order->getIncrementId();
                        $qty = array();
                        if ($productId == $item->getProductId()) {
                            $total_orders = $total_orders + 1;
                            $qty[0] = $item->getQtyOrdered();
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
                                $cmail[$i][10] = "";
                                $cmail[$i][11] = '';
                            }
                            $cmail[$i][4] = $_order->getGrandTotal();
                            $cmail[$i][6] = $order_id;
                            $cmail[$i][7] = $item->getQtyOrdered();
                            $cmail[$i][8] = $orderId;
                            $i++;

                            $quantity[0] = $quantity[0] + $qty[0];
                        }
                    }
                }
            }

            $emailto = "";
           
            if (count($cmail)) {
                $j = 1;
                //get order customer collection
                $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

                foreach ($cmail as $cmail1) {
                    $customer_name = $cmail1[2];
                    $customerName = str_replace("'", "", $customer_name);
                    $tocustomer = $cmail1[0];
                    $orderid = $cmail1[6];
                    $attVal = $productdetail->getOptions();
                 
                    if (sizeof($attVal)) {
                        foreach ($attVal as $optionVal) {

              $customTitle = $optionVal->getTitle();
              foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
              $sku = $valuesVal->getId();
              $dealstatus[0] = "processing";
              $dealstatus[2] = "complete";
$collection = Mage::getModel('sales/order')->getCollection()
->addFieldToFilter('status in (?)',$dealstatus) ;
$collection->join(
                        'sales/order_item',
                        '`sales/order_item`.order_id=`main_table`.entity_id',
                        array(
                            'proptions' => new Zend_Db_Expr('group_concat(`sales/order_item`.product_options SEPARATOR ",")'),
                            )
                        )
                    ->addAttributeToFilter('order_id',array('eq'=>$orderid));
$collection->getSelect()->group('entity_id');


foreach($collection as $item)
{
$value = $item->getProptions();

$value = unserialize($value);
$option_array = $value['options'];
$options = "";
if(count($option_array) > 0) {
foreach ($option_array as $_option) {
if ($_option['option_value'] == $sku) {
                                                        $customoptionTitle = $valuesVal->getTitle();
                                                        if ($productdetail->getSpecialPrice()) {
                                                            $customPrice = $productdetail->getSpecialPrice();
                                                        } else {
                                                            $customPrice = $productdetail->getPrice();
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

                    if ($quantity[0] < $totalcoupon) {

                        $postObject = new Varien_Object();
                        $storeName = Mage::getStoreConfig("design/head/default_title");
                        //$postObject->setData(array('content' => $post['content']));
                        $postObject->setData(array('storename' => $storeName, 'productname' => $currentproductname, 'purchasedqty' => $quantity[0], 'quantity' => $totalcoupon,'customtitle' => $customTitle, 'customoptiontitle' => $customoptionTitle));
                        $mailTemplate = Mage::getModel('core/email_template');
                        $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                        $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                        $mailTemplate->setTemplateSubject('Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title') . ' Order Quantity No:' . $i);

                        /* @var $mailTemplate Mage_Core_Model_Email_Template */
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                        Mage::getStoreConfig(self::XML_PATH_NO_EMAIL_TEMPLATE),
                                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                        $tocustomer,
                                        $customerName,
                                        array('deallist' => $postObject)
                        );

                        if (!$mailTemplate->getSentSuccess()) {
                            $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
                            $this->_redirect('*/*/');
                            return;
                        } else {
                            $emailto = "success";
                        }

                        $emailto = "success";
                    } else {
                        $inserting = "";
                        $orderid = $cmail1[6];
                        $orderedqty = floor($cmail1[7]);
                        $message = $cmail1[11] . '<br>' . $cmail1[10] . '<br>';
                        $subName = $cmail1[11];
                        for ($i = 1; $i <= $orderedqty; $i++) {
                            $orderCustUniqueId = Mage::getModel('ordercustomer/ordercustomer')->getCouponData($orderCustCollection, $orderid, $i);
                            if (isset($orderCustUniqueId)) {
                                $uniquenumber = $orderCustUniqueId;
                                $inserting = "No";
                            } else {
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
                            }



                            //Barcode Generation starts
                            $barcodeFileName = $uniquenumber;
                                $obj = Gclone_Barcode_Block_Barcode::generateBarcode($uniquenumber, $barcodeFileName);
                                $path = str_replace('index.php/', '',
                                                Mage::getBaseUrl()) . '/media/barcode/' . $barcodeFileName . '.png';
                                $uniquenumber .= '<img src=' . $path . '>';

//                            $uniquenumber .=Mage::getBaseUrl() . "couponvalidator/index/couponvalidation/code/" . $random_chars;

                            //Barcode Generation ends

                            $postObject = new Varien_Object();
                            if ($subName == '') {
                                $subjectname = 'Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title') . ' Order Quantity No:' . $i;
                            } else {
                                $subjectname = $subName;
                            }


                            $postObject->setData(array('total' => $cmail1[4], 'realorderid' => $cmail1[8], 'productname' => $currentproductname, 'product_description' => $product_description, 'customer_name' => $customer_name, 'couponcode' => $uniquenumber,'discount' => $discount, 'couponvalid' => $Couponvalid, 'companywebsite' => $Companywebsite, 'fineprint' => $fineprint, 'company_address' => $Companyname, 'currency_symbol' => $currencySymbol, 'product_worth' => $product_worth, 'quantity' => $orderedqty, 'message' => $message, 'subjectname' => $subjectname,'customtitle' => $customTitle, 'customoptiontitle' => $customoptionTitle));

                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                            $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
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

                            if (!$mailTemplate->getSentSuccess()) {
                                $this->_getSession()->addError("There is a problem in Sending Mail! Email not Sent!");
                                $this->_redirect('*/*/');
                                return;
                            } 


                            if ($inserting == "Yes") {
                            	$customTitle_insert = '';
                            	if($customTitle!= '')
                            	{
                            		$customTitle_insert = '-Custom Title:'.$customTitle.'-Custom Option Title:'.$customoptionTitle;
                            	}
                                    $insertCoupon .= "($orderid,'" . $customerName . "','" . addslashes($currentproductname).addslashes($customTitle_insert) . "','" . $orderedqty . "','" . $random_chars . "'," . $i . ",now(), '1')";

                                }
                                $emailto = "success";

                        }
                    }
                    $j = $j + 1;
                }
                // do inserting the records in ordercustomer table
                if (isset($insertCoupon)) {
                    $insertCoupon = str_ireplace("'1')", "'1'),", $insertCoupon);
					$str = $insertCoupon;
                	$length = strlen($str);
                	$insertCoupon = substr("$str", 0, $length - 1);
                    $giftemail->query("insert into " . $tprefix . "ordercustomer (order_id,customer_name,product_name,quantity,uniqueid,quantitynumber,created_time,couponstatus )values " . $insertCoupon);
                }
            } else {
                $this->_getSession()->addError($this->__('No orders For this Product'));
                $this->_redirect('*/*/');
                return;
            }
            if ($emailto == "success") {

                $this->_getSession()->addSuccess($this->__('Deal Coupons sent to the Customers.'));
                $this->_redirect('*/*/');
                return;
            }
        } else {
            $this->_getSession()->addError($this->__('No orders'));
            $this->_redirect('*/*/');
            return;
        }


        unset($giftemail);
        unset($resource);
        unset($productdetail);

        if ($redirectBack) {
            $this->_redirect('*/*/edit', array(
                'id' => $productId,
                '_current' => true
            ));
        } else if ($this->getRequest()->getParam('popup')) {
            $this->_redirect('*/*/created', array(
                '_current' => true,
                'id' => $productId,
                'edit' => $isEdit
            ));
        } else {
        	
            $this->_redirect('*/*/', array('store' => $storeId));
        }
        
    }

    /* CSV Format File */

    public function CSVFile() {
        $productId = (int) $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productId);
        $resource = Mage::getSingleton('core/resource');
        $orderTable = $resource->getTableName('sales/order');
        $read = $resource->getConnection('read');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        //get order customer collection
        $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

        //checking if the coupons send for that particular product
        $flatorderTable = $tprefix . "sales_flat_order_item";
        $total_orders = "0";
        $dealStatus[1] = "processing";
        $dealStatus[0] = "complete";
        $select = $read->select()
                        ->from(array('cp' => $orderTable))
                        ->join(array('pei' => $flatorderTable), 'pei.order_id=cp.entity_id', array())
                        ->where('pei.product_id=?', $productId)
                        ->where('cp.status in (?)', $dealStatus);

        $orders_list = $read->fetchAll($select);
        $ordersListCount = count($orders_list);

        if ($ordersListCount > 0) {
            $quantity = array();
            $quantity[0] = "0";
            $i = 0;
            $customerdetails = array();
            //gift message details
            $resultGiftMessage = array();
            $giftMessage = $read->fetchAll("Select *  from " . $tprefix . "gift_message  where product_id ='$productId'");
            foreach ($giftMessage as $value) {
                $resultGiftMessage[$value['order_id']] = $value;
            }
            //check all orders
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
                        if (isset($resultGiftMessage[$order_id]['email'])) {
                             $customerdetails[$i][0] = $resultGiftMessage[$order_id]['email'];
                             $customerdetails[$i][2] = $resultGiftMessage[$order_id]['recipient'];
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
            $template .= '"Order ID","Customer name","Unique Id","Deal Name","Coupons Purchased","Customer Email"' . "\n";
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
                        $template .= '"' . $customerdetails1[8] . '","' . $customerName . '","' . $uniqueId . '","' . $productName . '","' . $quantity . '","' . $customerdetails1[0] . '"' . "\n";
                    }
                }
            }
        } else {
            $template = '';
        }
        return $template;
    }

    /**

     * Save product action

     */
    public function saveAction() {
        $storeId = $this->getRequest()->getParam('store');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $productId = $this->getRequest()->getParam('id');
        $isEdit = (int) ($this->getRequest()->getParam('id') != null);

        $data = $this->getRequest()->getPost();
        if ($data) {
            if (!isset($data['product']['stock_data']['use_config_manage_stock'])) {
                $data['product']['stock_data']['use_config_manage_stock'] = 0;
            }
            $product = $this->_initProductSave();
            try {
                $product->save();
                if (!isset($productId)) {

                    $adminuser = Mage::getSingleton('admin/session')->getUser();
                    $roleId = implode('', $adminuser->getRoles());
                    $userId = $adminuser->getId();

                    if ($roleId != 1) {
                        $cities = array();
                        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'dealcity');
                        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId);
                        // to get the multiple cities in pages(drop down)
                        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
                            $value = $option['value'];
                            if ($value != '') {
                                $cities[$value] = $option['label'];
                            }
                        }
                        $userName = Mage::getSingleton('admin/session')->getUser()->getName();
                        $resource = Mage::getSingleton('core/resource');
                        $read = $resource->getConnection('read');
                        $tprefix = (string) Mage::getConfig()->getTablePrefix();
                        $cemail = $read->fetchRow("Select email  from " . $tprefix . "admin_user  where user_id = '1'");
                        $customeremail = $cemail['email'];
                        $productName = $data['product']['name'];
                        $city = $cities[$data['product']['dealcity'][0]];

                        //mail to admin
                        $postObject = new Varien_Object();
                        $postObject->setData(array('merchantname' => $userName, 'name' => $productName, 'city' => $city));
                        $mailTemplate = Mage::getModel('core/email_template');
                        $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                        $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                        $mailTemplate->setTemplateSubject("A New Deal From Merchant!");

                        /* @var $mailTemplate Mage_Core_Model_Email_Template */
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                        Mage::getStoreConfig(self::MERCHANT_PATH_ADDDEAL_TEMPLATE),
                                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                        $customeremail,
                                        Mage::getStoreConfig('design/head/default_title'),
                                        array('merchant' => $postObject)
                        );
                    }
                }
                $productId = $product->getId();
                $adminuser = Mage::getSingleton('admin/session')->getUser();
                $roleId = implode('', $adminuser->getRoles());
                $userId = $adminuser->getId();
                $resource1 = Mage::getSingleton('core/resource');
                $dealPayment = $resource1->getConnection('core_write');
                $tprefix = (string) Mage::getConfig()->getTablePrefix();
                $adminApproved = $dealPayment->fetchRow("Select product_id  from " . $tprefix . "deal_payment where product_id = " . $productId . " and admin_approved='yes'");
                $admincount = count($adminApproved['product_id']);
                if ($roleId != 1 && $admincount == 0) {
                    $resultproductid = $dealPayment->query("update  " . $tprefix . "catalog_product_entity_int set value=2 where entity_id  = '$productId'");
                }
                /**
                 * Do copying data to stores
                 */
                if (isset($data['copy_to_stores'])) {
                    foreach ($data['copy_to_stores'] as $storeTo => $storeFrom) {
                        $newProduct = Mage::getModel('catalog/product')
                                        ->setStoreId($storeFrom)
                                        ->load($productId)
                                        ->setStoreId($storeTo)
                                        ->save();
                    }
                }
                $this->_getSession()->addSuccess($this->__('The product has been saved.'));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                        ->setProductData($data);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect('*/*/edit', array(
                'id' => $productId,
                '_current' => true
            ));
        } else if ($this->getRequest()->getParam('popup')) {
            $this->_redirect('*/*/created', array(
                '_current' => true,
                'id' => $productId,
                'edit' => $isEdit
            ));
        } else {
            $this->_redirect('*/*/', array('store' => $storeId));
        }
    }

}