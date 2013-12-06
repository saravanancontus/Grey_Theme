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

class Gclone_Emailnewsletter_Block_Adminhtml_Emailnewsletter extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_emailnewsletter';
    $this->_blockGroup = 'emailnewsletter';
    $this->_headerText = Mage::helper('emailnewsletter')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('emailnewsletter')->__('Add Item');
    parent::__construct();
  }
}