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

class Gclone_Emailnewsletter_Block_Adminhtml_Emailnewsletter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('emailnewsletter_form', array('legend'=>Mage::helper('emailnewsletter')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('emailnewsletter')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('emailnewsletter')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('emailnewsletter')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('emailnewsletter')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('emailnewsletter')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('emailnewsletter')->__('Content'),
          'title'     => Mage::helper('emailnewsletter')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getEmailnewsletterData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getEmailnewsletterData());
          Mage::getSingleton('adminhtml/session')->setEmailnewsletterData(null);
      } elseif ( Mage::registry('emailnewsletter_data') ) {
          $form->setValues(Mage::registry('emailnewsletter_data')->getData());
      }
      return parent::_prepareForm();
  }
}