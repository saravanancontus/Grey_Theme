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

class Gclone_Dealreport_Block_Adminhtml_Dealreport_Grid extends Gclone_Dealreport_Block_Adminhtml_Dealreport_Griding
{
  /**
     * Sub report size
     *
     * @var int
     */
    protected $_subReportSize = 0;

    /**
     * Initialize Grid settings
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridDealsSold');

    }

    /**
     * Prepare collection object for grid
     *
     * @return Mage_Adminhtml_Block_Report_Product_Sold_Grid
     */
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Report_Product_Sold_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('end_date', array(
            'header'    =>Mage::helper('reports')->__('End Date'),
            'align'     =>'right',
            'width'     =>'50px',
            'index'     =>'end_date',
            'type'      =>'number'
            ));
         $this->addColumn('name', array(
            'header'    =>Mage::helper('reports')->__('Deal Name'),
            'align'     =>'right',
            'width'     =>'200px',
            'index'     =>'name',
            'type'      =>'number'
            ));
        $this->addColumn('deal_status', array(
            'header'    =>Mage::helper('reports')->__('Deal Achieved'),
            'index'     =>'deal_status',
            'width'     =>'50px',
            'type'      =>'number'
            ));
        $this->addColumn('target', array(
            'header'    =>Mage::helper('reports')->__('Target'),
            'index'     =>'target',
            'width'     =>'50px',
            'type'      =>'number'
            ));
        $this->addColumn('qty_purchased', array(
            'header'    =>Mage::helper('reports')->__('Quantity Purchased'),
            'index'     =>'qty_purchased',
            'width'     =>'50px',
            'type'      =>'number'
            ));
        $this->addColumn('price', array(
            'header'    =>Mage::helper('reports')->__('Price'),
            'index'     =>'price',
            'width'     =>'50px',
            'type'      =>'number'
            ));
        $this->addColumn('dealcity', array(
            'header'    =>Mage::helper('reports')->__('City'),
            'index'     =>'dealcity',
            'width'     =>'200px',
            'type'      =>'number'
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }

}