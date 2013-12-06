<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */
class Contus_Share_Block_Adminhtml_Share_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('share_form', array('legend'=>Mage::helper('share')->__('Item information')));

        $fieldset->addField('facebook', 'text', array(
          'label'     => Mage::helper('share')->__('Facebook'),
          'name'      => 'facebook',
            ));

        $fieldset->addField('twitter', 'text', array(
          'label'     => Mage::helper('share')->__('Twitter'),
          'name'      => 'twitter',
            ));

        $fieldset->addField('linkedin', 'text', array(
          'label'     => Mage::helper('share')->__('Linkedin'),
          'name'      => 'linkedin',
            ));

        $fieldset->addField('facebookfans', 'text', array(
          'label'     => Mage::helper('share')->__('Facebook Fans Id'),
          'name'      => 'facebookfans',
            ));

        if ( Mage::getSingleton('adminhtml/session')->getShareData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getShareData());
            Mage::getSingleton('adminhtml/session')->setShareData(null);
        } elseif ( Mage::registry('share_data') ) {
            $form->setValues(Mage::registry('share_data')->getData());
        }
        return parent::_prepareForm();
    }
}