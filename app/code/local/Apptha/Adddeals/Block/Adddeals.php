<?php
class Apptha_Adddeals_Block_Adddeals extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAdddeals()     
     { 
        if (!$this->hasData('adddeals')) {
            $this->setData('adddeals', Mage::registry('adddeals'));
        }
        return $this->getData('adddeals');
        
    }
}