<?php
class Apptha_Advancedreports_Block_Advancedreports extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAdvancedreports()     
     { 
        if (!$this->hasData('advancedreports')) {
            $this->setData('advancedreports', Mage::registry('advancedreports'));
        }
        return $this->getData('advancedreports');
        
    }
}