<?php

class Apptha_Adddeals_Block_Adminhtml_Adddeals_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('adddeals_form', array('legend'=>Mage::helper('adddeals')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('adddeals')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('adddeals')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('adddeals')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('adddeals')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('adddeals')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('adddeals')->__('Content'),
          'title'     => Mage::helper('adddeals')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getAdddealsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getAdddealsData());
          Mage::getSingleton('adminhtml/session')->setAdddealsData(null);
      } elseif ( Mage::registry('adddeals_data') ) {
          $form->setValues(Mage::registry('adddeals_data')->getData());
      }
      return parent::_prepareForm();
  }
}