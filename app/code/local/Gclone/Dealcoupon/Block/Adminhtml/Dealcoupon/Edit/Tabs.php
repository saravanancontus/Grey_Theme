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

class Gclone_Dealcoupon_Block_Adminhtml_Dealcoupon_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('dealcoupon_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('dealcoupon')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('dealcoupon')->__('Item Information'),
          'title'     => Mage::helper('dealcoupon')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('dealcoupon/adminhtml_dealcoupon_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}