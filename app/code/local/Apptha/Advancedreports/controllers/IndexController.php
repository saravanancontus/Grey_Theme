<?php
class Apptha_Advancedreports_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/advancedreports?id=15 
    	 *  or
    	 * http://site.com/advancedreports/id/15 	
    	 */
    	/* 
		$advancedreports_id = $this->getRequest()->getParam('id');

  		if($advancedreports_id != null && $advancedreports_id != '')	{
			$advancedreports = Mage::getModel('advancedreports/advancedreports')->load($advancedreports_id)->getData();
		} else {
			$advancedreports = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($advancedreports == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$advancedreportsTable = $resource->getTableName('advancedreports');
			
			$select = $read->select()
			   ->from($advancedreportsTable,array('advancedreports_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$advancedreports = $read->fetchRow($select);
		}
		Mage::register('advancedreports', $advancedreports);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}