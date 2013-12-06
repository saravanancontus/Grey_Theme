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

class Gclone_Emailnewsletter_Block_Adminhtml_Emailnewsletter_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'emailnewsletter';
        $this->_controller = 'adminhtml_emailnewsletter';
        
        $this->_updateButton('save', 'label', Mage::helper('emailnewsletter')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('emailnewsletter')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('emailnewsletter_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'emailnewsletter_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'emailnewsletter_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('emailnewsletter_data') && Mage::registry('emailnewsletter_data')->getId() ) {
            return Mage::helper('emailnewsletter')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('emailnewsletter_data')->getTitle()));
        } else {
            return Mage::helper('emailnewsletter')->__('Add Item');
        }
    }
}