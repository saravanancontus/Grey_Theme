<?php

class Apptha_Advancedreports_Block_Adminhtml_Advancedreports_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('advancedreports_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('advancedreports')->__('All Reports'));
    }

    protected function _beforeToHtml() {

        $sales_active = false;
        $product_active = false;
        $transaction_active = false;
        $timetopurchase_active = false;

        if ($this->getRequest()->getParam('page')) {
            switch ($this->getRequest()->getParam('page')) {
                case "product":
                    $product_active = true;
                    break;
                case "transactions":
                    $transaction_active = true;
                    break;
                case "timetopurchase":
                    $timetopurchase_active = true;
                    break;

                default:
                    $sales_active = true;
            }
        } else {
            $sales_active = true;
        }
        
        $this->addTab('sales_section', array(
            'label' => Mage::helper('advancedreports')->__('Sales Report'),
            'title' => Mage::helper('advancedreports')->__('Sales Report'),
            'content' => $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit_tab_sales')->toHtml(),
            'active' => $sales_active,
        ));


        $this->addTab('product_section', array(
            'label' => Mage::helper('advancedreports')->__('Product Report'),
            'title' => Mage::helper('advancedreports')->__('Product Report'),
            'content' => $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit_tab_product')->toHtml(),
            'active' => $product_active,
        ));


        $this->addTab('transactions_section', array(
            'label' => Mage::helper('advancedreports')->__('Transactions Report'),
            'title' => Mage::helper('advancedreports')->__('Transactions Report'),
            'content' => $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit_tab_transactions')->toHtml(),
            'active' => $transaction_active,
        ));

        $this->addTab('timetopurchase_section', array(
            'label' => Mage::helper('advancedreports')->__('Time to Purchase Report'),
            'title' => Mage::helper('advancedreports')->__('Time to Purchase Report'),
            'content' => $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit_tab_timetopurchase')->toHtml(),
            'active' => $timetopurchase_active,
        ));





        return parent::_beforeToHtml();
    }

}