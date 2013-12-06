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

class Gclone_Dealreport_Block_Adminhtml_Dealreport extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
   
    $this->_controller = 'adminhtml_dealreport';
    $this->_blockGroup = 'dealreport';
    $this->_headerText = Mage::helper('dealreport')->__('Deal Report');
    parent::__construct();
    $this->_removeButton('add');
  }
}