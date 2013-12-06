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

class Gclone_Couponvalidator_Block_Adminhtml_Couponvalidator extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
     
    $this->_controller = 'adminhtml_couponvalidator';
    $this->_blockGroup = 'couponvalidator';
    $this->_headerText = Mage::helper('couponvalidator')->__('Coupon Validator');
    parent::__construct();
    $this->_removeButton('add');
  }
}