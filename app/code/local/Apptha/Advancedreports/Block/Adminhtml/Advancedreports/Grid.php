<?php

class Apptha_Advancedreports_Block_Adminhtml_Advancedreports_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
    {
        parent::__construct();


        $this->setId('sales_order');
        $this->setUseAjax(true);
        $this->setDefaultSort('base_grand_total');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', array(
            'header'=> Mage::helper('sales')->__('S no'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'entity_id',
            'filter' => false,
            'sortable'  => false

        ));


            $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Date'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
            'filter' => false,
            'sortable'  => false
        ));


             $this->addColumn('subtotal', array(
            'header' => Mage::helper('sales')->__('Revenue'),
            'index' => 'subtotal',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
            'filter' => false,
            'sortable'  => false
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('Percent'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'filter' => false,
            'sortable'  => false
        ));      
  
       

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
       // $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    
    public function getMainButtonsHtml()
    {
    return '';
    }





}