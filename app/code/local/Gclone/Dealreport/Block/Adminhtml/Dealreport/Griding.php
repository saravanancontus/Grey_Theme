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
class Gclone_Dealreport_Block_Adminhtml_Dealreport_Griding extends Mage_Adminhtml_Block_Widget_Griding {

    protected $_storeSwitcherVisibility = true;
    protected $_dateFilterVisibility = true;
    protected $_exportVisibility = true;
    protected $_subtotalVisibility = false;
    protected $_filters = array();
    protected $_defaultFilters = array(
        'report_from' => '',
        'report_to' => '',
        'report_period' => 'day',
        'deal_name' => 'day'
    );
    protected $_subReportSize = 5;
    protected $_grandTotals;
    protected $_errors = array();
    /**
     * stores current currency code
     */
    protected $_currentCurrencyCode = null;

    public function __construct() {
        parent::__construct();
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        $this->setTemplate('dealreport/griding.phtml');
        $this->setUseAjax(false);
        $this->setCountTotals(true);
    }

    /* protected function _prepareLayout() {

        $this->setChild('refresh_button',
                        $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('adminhtml')->__('Search'),
                            'onclick' => $this->getRefreshButtonCallback(),
                            'class' => 'task'
                        ))
        );
        parent::_prepareLayout();
        return $this;
    } */

    protected function _prepareColumns() {
        foreach ($this->_columns as $_column) {
            $_column->setSortable(false);
        }

        parent::_prepareColumns();
    }

    public function getdealdetails() {
        $dealId = $this->getRequest()->getParam('id');
        $dealdetails = array();
        if ($dealId!= 0) {
            $model = Mage::getModel('catalog/product'); //getting product model
            $_product = $model->load($dealId);

            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('catalog_read');
            $orderTable = $resource->getTableName('sales/order');
            $orderItemTable = $resource->getTableName('sales/order_item');
            $dealstatus[0] = "processing";
            $dealstatus[1] = "complete";
            $select = $read->select()
                            ->from(array('cp' => $orderTable), array('totalcount' => 'sum(cp.total_qty_ordered)'))
                            ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id', array())
                            ->where('cp.status in (?)', $dealstatus)
                            ->where('pei.product_id in (?)', $dealId);

            $orders_list = $read->fetchAll($select);

            $quantityOrdered = floor($orders_list[0]['totalcount']);
            $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
            $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
            $DealName = $_product->getName();
            $Target = $_product->gettarget_number();
            $Price = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol() . floor($_product->getSpecialPrice());
            $City = $_product->getAttributeText('dealcity');
            $i = 1;
            if (count($City) == 1) {
                $city = $City;
            }
            foreach ($City as $item) {
                $city .= $item;
                if (count($City) != $i || $i == 1) {
                    $city .= ', ';
                }
                $i++;
            }

            if ($Target <= $quantityOrdered) {
                $Achieved = 'Yes';
            } else {
                $Achieved = 'No';
            }
            if ($quantityOrdered == 0) {
                $quantityOrdered = "0";
            }
            $dealdetails[] = $ProductFromDate;
            $dealdetails[] = $ProductToDate;
            $dealdetails[] = $DealName;
            $dealdetails[] = $Achieved;
            $dealdetails[] = $Target;
            $dealdetails[] = $quantityOrdered;
            $dealdetails[] = $Price;
            $dealdetails[] = $city;
        }
        return $dealdetails;
    }

    public function getcustomerdetails() {

        $dealId = $this->getRequest()->getParam('id');
        $resource = Mage::getSingleton('core/resource');
        $giftemail = $resource->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $total_orders = "0";
        $read = $resource->getConnection('catalog_read');

        $dealstatus[0] = "processing";
        $dealstatus[1] = "complete";
        $orderItemcollection = Mage::getModel('sales/order_item')
                        ->getCollection();
        $orderCollection = Mage::getModel('sales/order')
                        ->getCollection()->addAttributeToFilter('status', array('in' => $dealstatus));



        $fetch_list = array();
        $quantity = array();
        $quantity[0] = "0";
        $cmail = array();
        $customerdetails = array();
        if (count($orderCollection) > 0) {
            $i = 0;

            //gift message details
            $resultGiftMessage = array();
            $giftMessage = $read->fetchAll("Select *  from " . $tprefix . "gift_message  where product_id ='$productId'");
            foreach ($giftMessage as $value) {
                $resultGiftMessage[$value['order_id']] = $value;
            }
            //get order customer collection
            $orderCustCollection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

            foreach ($orderCollection as $_order) {
                $order_id = $_order->getEntityId();
                foreach ($orderItemcollection as $item) {
                    $_orderId = $item->getOrderId();
                    if ($order_id == $_orderId) {
                        $orderId = $_order->getIncrementId();
                        $qty = array();

                         

                        if ( $dealId == $item->getProductId()) {
                            $total_orders = $total_orders + 1;
                            $qty[0] = $item->getQtyOrdered();
                            for ($j = 1; $j <= $qty[0]; $j++) {
                                $cmail[$i][1] = $orderId;
                                $cmail[$i][3] = Mage::getBaseUrl() . 'sales/order/print/order_id/' . $order_id . '/';
                                $customerId = $_order->getCustomerId();

                                if (isset($resultGiftMessage[$order_id]['email'])) {
                                    $cmail[$i][0] = $resultGiftMessage[$order_id]['email'];
                                    $cmail[$i][2] = $resultGiftMessage[$order_id]['recipient'];
                                } else {
                                    $cmail[$i][0] = $_order->getCustomerEmail();
                                    $cmail[$i][2] = floor($qty[0]);
                                }
                                if ($j == 1)
                                    $cmail[$i][3] = $_order->getCustomerFirstname() . ' ' . $_order->getCustomerLastname();
                                else
                                    $cmail[$i][3] = '-';
                                $quantity[0] = $quantity[0] + $qty[0];
                                $customername = $cmail[$i][1];

                                $orderCustUniqueId=Mage::getModel('ordercustomer/ordercustomer')->getCouponData($orderCustCollection, $order_id, $j);
                                if (isset($orderCustUniqueId)) {
                                    $cmail[$i][4] = $orderCustUniqueId;
                                } else {
                                    $cmail[$i][4] = "Coupon not sent";
                                }
                                if ($j == 1)
                                    $cmail[$i][5] = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol() . floor($_order->getGrandTotal());
                                else
                                    $cmail[$i][5] = '-';
                                $i++;

                                $quantity[0] = $quantity[0] + $qty[0];
                            }
                        }

                    }
                }
            }
        }
        return $cmail;
    }

    /*
     * product collection
     */

    public function getDeals() {
        $dealId = $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToSelect('name')
                        ->addAttributeToSelect('merchant')
                        ->addAttributeToFilter('entity_id', $dealId);
        $adminuser = Mage::getSingleton('admin/session')->getUser();
        $roleId = implode('', $adminuser->getRoles());
        $userId = $adminuser->getId();
        if ($roleId != 1) {
            $product->addFieldToFilter(array(
                array('attribute' => 'merchant', 'eq' => $userId),
            ));
        }
        return $product;
    }

    protected function _prepareCollection() {

        $filter = $this->getParam($this->getVarNameFilter(), null);

        if (is_null($filter)) {
            $filter = $this->_defaultFilter;
        }

        if (is_string($filter)) {
            $data = array();
            $filter = base64_decode($filter);
            parse_str(urldecode($filter), $data);

            if (!isset($data['report_from'])) {
                // getting all reports from 2001 year
                $date = new Zend_Date(mktime(0, 0, 0, 1, 1, 2001));
                $data['report_from'] = $date->toString($this->getLocale()->getDateFormat('short'));
            }

            if (!isset($data['report_to'])) {
                // getting all reports from 2001 year
                $date = new Zend_Date();
                $data['report_to'] = $date->toString($this->getLocale()->getDateFormat('short'));
            }

            $this->_setFilterValues($data);
        } else if ($filter && is_array($filter)) {
            $this->_setFilterValues($filter);
        } else if (0 !== sizeof($this->_defaultFilter)) {
            $this->_setFilterValues($this->_defaultFilter);
        }

        $collection = Mage::getResourceModel('reports/report_collection');

        $collection->setPeriod($this->getFilter('report_period'));

        if ($this->getFilter('report_from') && $this->getFilter('report_to')) {
            /**
             * Validate from and to date
             */
            try {
                $from = $this->getLocale()->date($this->getFilter('report_from'), Zend_Date::DATE_SHORT, null, false);
                $to = $this->getLocale()->date($this->getFilter('report_to'), Zend_Date::DATE_SHORT, null, false);

                $collection->setInterval($from, $to);
            } catch (Exception $e) {
                $this->_errors[] = Mage::helper('reports')->__('Invalid date specified.');
            }
        }

        /**
         * Getting and saving store ids for website & group
         */
        if ($this->getRequest()->getParam('store')) {
            $storeIds = array($this->getParam('store'));
        } else if ($this->getRequest()->getParam('website')) {
            $storeIds = Mage::app()->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
        } else if ($this->getRequest()->getParam('group')) {
            $storeIds = Mage::app()->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
        } else {
            $storeIds = array('');
        }
        $collection->setStoreIds($storeIds);

        if ($this->getSubReportSize() !== null) {
            $collection->setPageSize($this->getSubReportSize());
        }

        $this->setCollection($collection);

        Mage::dispatchEvent('adminhtml_widget_grid_filter_collection',
                        array('collection' => $this->getCollection(), 'filter_values' => $this->_filterValues)
        );
    }

    protected function _setFilterValues($data) {
        foreach ($data as $name => $value) {
            //if (isset($data[$name])) {
            $this->setFilter($name, $data[$name]);
            //}
        }
        return $this;
    }

    /**
     * Set visibility of store switcher
     *
     * @param boolean $visible
     */
    public function setStoreSwitcherVisibility($visible=true) {
        $this->_storeSwitcherVisibility = $visible;
    }

    /**
     * Return visibility of store switcher
     *
     * @return boolean
     */
    public function getStoreSwitcherVisibility() {
        return $this->_storeSwitcherVisibility;
    }

    /**
     * Return store switcher html
     *
     * @return string
     */
    public function getStoreSwitcherHtml() {
        return $this->getChildHtml('store_switcher');
    }

    /**
     * Set visibility of date filter
     *
     * @param boolean $visible
     */
    public function setDateFilterVisibility($visible=true) {
        $this->_dateFilterVisibility = $visible;
    }

    /**
     * Return visibility of date filter
     *
     * @return boolean
     */
    public function getDateFilterVisibility() {
        return $this->_dateFilterVisibility;
    }

    /**
     * Set visibility of export action
     *
     * @param boolean $visible
     */
    public function setExportVisibility($visible=true) {
        $this->_exportVisibility = $visible;
    }

    /**
     * Return visibility of export action
     *
     * @return boolean
     */
    public function getExportVisibility() {
        return $this->_exportVisibility;
    }

    /**
     * Set visibility of subtotals
     *
     * @param boolean $visible
     */
    public function setSubtotalVisibility($visible=true) {
        $this->_subtotalVisibility = $visible;
    }

    /**
     * Return visibility of subtotals
     *
     * @return boolean
     */
    public function getSubtotalVisibility() {
        return $this->_subtotalVisibility;
    }

    public function getPeriods() {
        return $this->getCollection()->getPeriods();
    }

    public function getDateFormat() {
        return $this->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }

    /**
     * Return refresh button html
     */
    public function getRefreshButtonHtml() {
        return $this->getChildHtml('refresh_button');
    }

    public function setFilter($name, $value) {
        if ($name) {
            $this->_filters[$name] = $value;
        }
    }

    public function getFilter($name) {
        if (isset($this->_filters[$name])) {
            return $this->_filters[$name];
        } else {
            return ($this->getRequest()->getParam($name)) ? htmlspecialchars($this->getRequest()->getParam($name)) : '';
        }
    }

    public function setSubReportSize($size) {
        $this->_subReportSize = $size;
    }

    public function getSubReportSize() {
        return $this->_subReportSize;
    }

    /**
     * Retrieve locale
     *
     * @return Mage_Core_Model_Locale
     */
    public function getLocale() {
        if (!$this->_locale) {
            $this->_locale = Mage::app()->getLocale();
        }
        return $this->_locale;
    }

    /**
     * Add new export type to grid
     *
     * @param   string $url
     * @param   string $label
     * @return  Mage_Adminhtml_Block_Widget_Grid
     */
    public function addExportType($url, $label) {
        $this->_exportTypes[] = new Varien_Object(
                        array(
                            'url' => $this->getUrl($url,
                                    array(
                                        '_current' => true,
                                        'filter' => $this->getParam($this->getVarNameFilter(), null)
                                    )
                            ),
                            'label' => $label
                        )
        );
        return $this;
    }

    public function getReport($from, $to) {
        if ($from == '') {
            $from = $this->getFilter('report_from');
        }
        if ($to == '') {
            $to = $this->getFilter('report_to');
        }
        $totalObj = Mage::getModel('reports/totals');
        $this->setTotals($totalObj->countTotals($this, $from, $to));
        $this->addGrandTotals($this->getTotals());
        return $this->getCollection()->getReport($from, $to);
    }

    public function addGrandTotals($total) {
        $totalData = $total->getData();
        foreach ($totalData as $key => $value) {
            $_column = $this->getColumn($key);
            if ($_column->getTotal() != '') {
                $this->getGrandTotals()->setData($key, $this->getGrandTotals()->getData($key) + $value);
            }
        }
        /*
         * recalc totals if we have average
         */
        foreach ($this->getColumns() as $key => $_column) {
            if (strpos($_column->getTotal(), '/') !== FALSE) {
                list($t1, $t2) = explode('/', $_column->getTotal());
                if ($this->getGrandTotals()->getData($t2) != 0) {
                    $this->getGrandTotals()->setData(
                            $key,
                            (float) $this->getGrandTotals()->getData($t1) / $this->getGrandTotals()->getData($t2)
                    );
                }
            }
        }
    }

    public function getGrandTotals() {
        if (!$this->_grandTotals) {
            $this->_grandTotals = new Varien_Object();
        }
        return $this->_grandTotals;
    }

    public function getPeriodText() {
        return $this->__('Start Date');
    }

    function customercolumns() {
        $columns = array('Customer OrderID', 'Customer Name', 'Customer Email', 'Quantity Purchased', 'Coupon Code', 'Total Amount');
        return $columns;
    }

    /**
     * Retrieve grid as CSV
     *
     * @return unknown
     */
    public function getCsv() {
        $csv = '';
        $this->_prepareGrid();

        $data = array();
        foreach ($this->customercolumns() as $column) {
            $data[] = '"' . $column . '"';
        }
        $csv.= implode(',', $data) . "\n";
        $cdata = $this->getcustomerdetails();
        foreach ($cdata as $cdetail) {
            $csv.= implode(',', array_values($cdetail)) . "\n";
        }


        return $csv;
    }

    /**
     * Retrieve grid as Excel Xml
     *
     * @return unknown
     */
    public function getXml($filename = '') {
        $this->_prepareGrid();

        $data = array();
        $row = array();
        foreach ($this->customercolumns() as $column) {
            $row[] = '"' . $column . '"';
        }
      
        $data[] = $row;
        $cdata = $this->getcustomerdetails();
        
        foreach ($cdata as $cdetail) {
            $data[] = array_values($cdetail);
        }
        $xmlObj = new Varien_Convert_Parser_Xml_Excel();
        $xmlObj->setVar('single_sheet', $filename);
        $xmlObj->setData($data);
        $xmlObj->unparse();

        return $xmlObj->getData();
    }

    public function getSubtotalText() {
        return $this->__('Subtotal');
    }

    public function getTotalText() {
        return $this->__('Total');
    }

    public function getEmptyText() {
        return $this->__('No records found for this Deal.');
    }

    public function getCountTotals() {
        $totals = $this->getGrandTotals()->getData();
        if (parent::getCountTotals() && count($totals)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * onlick event for refresh button to show alert if fields are empty
     *
     * @return string
     */
    public function getRefreshButtonCallback() {
        return "{$this->getJsObjectName()}.doFilter();";
        return "if ($('deal_name').value == 0) {alert('" . $this->__('Please Select a Deal Name.') . "'); return false;}else {$this->getJsObjectName()}.doFilter();";
    }

    /**
     * Retrieve errors
     *
     * @return array
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * Retrieve correct currency code for select website, store, group
     *
     * @return string
     */
    public function getCurrentCurrencyCode() {
        if (is_null($this->_currentCurrencyCode)) {
            if ($this->getRequest()->getParam('store')) {
                $this->_currentCurrencyCode = Mage::app()->getStore($this->getRequest()->getParam('store'))->getBaseCurrencyCode();
            } else if ($this->getRequest()->getParam('website')) {
                $this->_currentCurrencyCode = Mage::app()->getWebsite($this->getRequest()->getParam('website'))->getBaseCurrencyCode();
            } else if ($this->getRequest()->getParam('group')) {
                $this->_currentCurrencyCode = Mage::app()->getGroup($this->getRequest()->getParam('group'))->getWebsite()->getBaseCurrencyCode();
            } else {
                $this->_currentCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
            }
        }
        return $this->_currentCurrencyCode;
    }

}
