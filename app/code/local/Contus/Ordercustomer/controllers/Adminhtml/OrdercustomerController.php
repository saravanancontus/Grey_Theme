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
class Contus_Ordercustomer_Adminhtml_OrdercustomerController extends Mage_Adminhtml_Controller_Action {
    const XML_PATH_COUPON_TEMPLATE = 'dealcoupon/email/coupon_template';
    const XML_PATH_OWNER_TEMPLATE = 'dealcoupon/email/owner_template';
    const XML_PATH_NO_EMAIL_TEMPLATE = 'dealcoupon/email/email_template';
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'dealcoupon/email/sender_email_identity';

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('ordercustomer/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ordercustomer/ordercustomer')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('ordercustomer_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('ordercustomer/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('ordercustomer/adminhtml_ordercustomer_edit'))
                    ->_addLeft($this->getLayout()->createBlock('ordercustomer/adminhtml_ordercustomer_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordercustomer')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('filename');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS;
                    $uploader->save($path, $_FILES['filename']['name']);
                } catch (Exception $e) {

                }

                //this way the name is saved in DB
                $data['filename'] = $_FILES['filename']['name'];
            }


            $model = Mage::getModel('ordercustomer/ordercustomer');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ordercustomer')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ordercustomer')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('ordercustomer/ordercustomer');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $ordercustomerIds = $this->getRequest()->getParam('ordercustomer');
        if (!is_array($ordercustomerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($ordercustomerIds as $ordercustomerId) {
                    $ordercustomer = Mage::getModel('ordercustomer/ordercustomer')->load($ordercustomerId);
                    $ordercustomer->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($ordercustomerIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $ordercustomerIds = $this->getRequest()->getParam('ordercustomer');
        if (!is_array($ordercustomerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($ordercustomerIds as $ordercustomerId) {
                    $ordercustomer = Mage::getSingleton('ordercustomer/ordercustomer')
                                    ->load($ordercustomerId)
                                    ->setCouponstatus($this->getRequest()->getParam('status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($ordercustomerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massSendCouponAction() {
        $ordercustomerIds = $this->getRequest()->getParam('ordercustomer');

        if (!is_array($ordercustomerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($ordercustomerIds as $ordercustomerId) {
                    $ordercustomer = Mage::getSingleton('ordercustomer/ordercustomer')
                                    ->load($ordercustomerId);

                    $orderId = $ordercustomer->getOrderId();
                    $uniquenumber = $ordercustomer->getUniqueid();
                    $customer_name = $ordercustomer->getCustomerName();
                    $customerName = str_replace("'", "", $customer_name);
                    $quantityNumber = $ordercustomer->getQuantitynumber();

                    $model = Mage::getModel('catalog/product');
                    $tprefix = (string) Mage::getConfig()->getTablePrefix();
                    $resource = Mage::getSingleton('core/resource');
                    $currentdeal = $resource->getConnection('core_write');
                    $read = $resource->getConnection('catalog_read');
                    $orderTable = $resource->getTableName('sales/order');
                    $orderItemTable = $resource->getTableName('sales/order_item');
                    $dealstatus[0] = "processing";
                    $dealstatus[1] = "complete";
                    $selectOrders = $read->select()
                                    ->from(array('cp' => $orderTable))
                                    ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id')
                                    ->where('pei.order_id =?', $orderId);

                    $orders_list = $read->fetchRow($selectOrders);
                    $realOrderId = $orders_list['increment_id'];
                    $grandTotal = $orders_list['grand_total'];
                    $productId = $orders_list['product_id'];
                    $orderedqty = floor($orders_list['total_qty_ordered']);
                    $tocustomer = $orders_list['customer_email'];

                    //currency symbol
                    $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
                    $_product = $model->load($productId);
                   // $currentproductimage = Mage::helper('catalog/image')->init($_product, 'image')->resize(386, 338);

                    $discount_price = $_product->getPrice() - $_product->getSpecialPrice();
                    $discount = ($discount_price * 100) / $_product->getPrice();
                    $discount = round($discount);
                    $currentdiscount = $discount;
                    $Couponvalid = $_product->getEnjoyby();
                    $Companywebsite = $_product->getCustomer_website();
                    $Companyname = $_product->getCustomersite();
                    $product_worth = $_product->getSpecialPrice();
                    $currentproductname = $_product->getName();
                    $product_description = $_product->getDescription();
                    $fineprint = $_product->getFineprint();

                    //Barcode Generation starts
                    $enableBarcode = Mage::getStoreConfig('barcode/defaultcode/status');
                    $barcodeFileName = $uniquenumber;
                    if ($enableBarcode != 0) {
                    $obj = Gclone_Barcode_Block_Barcode::generateBarcode($uniquenumber, $barcodeFileName);
                    $path = str_replace('index.php/', '', Mage::getBaseUrl()) . '/media/barcode/' . $barcodeFileName . '.png';
                    $uniquenumber = '<img src=' . $path . '>';
                    }
                    //Barcode Generation ends

                    $postObject = new Varien_Object();

                    $subjectname = 'Coupon Confirmation From ' . Mage::getStoreConfig('design/head/default_title') . ' Order Quantity No:' . $quantityNumber;


                    $postObject->setData(array('total' => $grandTotal, 'realorderid' => $realOrderId, 'product_description' => $product_description, 'customer_name' => $customer_name, 'productname' => $currentproductname, 'couponcode' => $uniquenumber, 'productimage' => $currentproductimage, 'discount' => $discount, 'couponvalid' => date('F j, Y', strtotime($Couponvalid)), 'companywebsite' => $Companywebsite, 'fineprint' => $fineprint, 'company_address' => $Companyname, 'currency_symbol' => $currencySymbol, 'product_worth' => floor($product_worth), 'quantity' => $orderedqty, 'message' => $message, 'subjectname' => $subjectname));

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
                }
                if ($mailTemplate->getSentSuccess()) {
                    $this->_getSession()->addSuccess(
                            $this->__('Coupon has been send %d record(s) successfully', count($ordercustomerIds))
                    );
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'dealcoupon.csv';
        $content = $this->getLayout()->createBlock('ordercustomer/adminhtml_ordercustomer_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'dealcoupon.xml';
        $content = $this->getLayout()->createBlock('ordercustomer/adminhtml_ordercustomer_grid')
                        ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}