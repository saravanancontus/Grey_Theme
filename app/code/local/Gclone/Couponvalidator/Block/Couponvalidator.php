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

class Gclone_Couponvalidator_Block_Couponvalidator extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
            
		return parent::_prepareLayout();
    }
    
     public function getCouponvalidator()     
     { 
        if (!$this->hasData('couponvalidator')) {
            $this->setData('couponvalidator', Mage::registry('couponvalidator'));
        }
        return $this->getData('couponvalidator');
        
    }
}