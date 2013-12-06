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

class Gclone_Constantcontact_Model_Constantcontact_emailtype
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'html', 'label'=>Mage::helper('adminhtml')->__('HTML')),
            array('value'=>'text', 'label'=>Mage::helper('adminhtml')->__('Text')),
        );
    }

}