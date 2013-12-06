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

class Gclone_Couponvalidator_Block_Adminhtml_Couponvalidator_Grid extends Gclone_Couponvalidator_Block_Adminhtml_Couponvalidator_Griding
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
        $this->addColumn('increment_id', array(
			'header'    => Mage::helper('reports')->__('Order Id'),
			'width'     => '120px',
			'index'     => 'increment_id',
      ));

      $this->addColumn('customer_name', array(
          'header'    => Mage::helper('reports')->__('Customer Name'),
          'align'     =>'left',
          'index'     => 'customer_name',
      ));


       $this->addColumn('product_name', array(
          'header'    => Mage::helper('reports')->__('Product name'),
          'align'     =>'left',
          'index'     => 'product_name',
      ));

	  $this->addColumn('quantity', array(
          'header'    => Mage::helper('reports')->__('Quantity'),
          'align'     =>'left',
              'width'     => '90px',
          'index'     => 'quantity',
      ));

	  $this->addColumn('quantitynumber', array(
          'header'    => Mage::helper('reports')->__('Coupon Number#'),
          'align'     =>'left',
          'index'     => 'quantitynumber',
      ));

	  $this->addColumn('uniqueid', array(
          'header'    => Mage::helper('reports')->__('Coupon Code'),
          'align'     =>'left',
          'index'     => 'uniqueid',
      ));

	   $this->addColumn('created_time', array(
          'header'    => Mage::helper('reports')->__('Created Date'),
          'align'     =>'left',
          'type' => 'datetime',
               'width'     => '170px',
          'index'     => 'created_time',
      ));

            $this->addColumn('status', array(
          'header'    => Mage::helper('reports')->__('Status'),
          'align'     =>'left',

               'width'     => '170px',
          'index'     => 'status',
      ));

        $this->addExportType('*/*/exportSoldCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportSoldExcel', Mage::helper('reports')->__('Excel'));

        return parent::_prepareColumns();
    }
}
