<?php

class Apptha_DealProduct_Model_Observer
{    
    public function hookIntoCatalogProductNewAction($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo 'Inside hookIntoCatalogProductNewAction observer...'; exit;
        //Implement the "catalog_product_new_action" hook
        return $this;    	
    }
    
    public function hookIntoCatalogProductEditAction($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo 'Inside hookIntoCatalogProductEditAction observer...'; exit;
        //Implement the "catalog_product_edit_action" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogProductPrepareSave($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogProductPrepareSave observer...'; exit;
        //Implement the "catalog_product_prepare_save" hook
        return $this;    	
    }

    public function hookIntoSalesOrderItemSaveAfter($observer)
    {
        //$event = $observer->getEvent();
        //echo 'Inside hookIntoSalesOrderItemSaveAfter observer...'; exit;
        //Implement the "sales_order_item_save_after" hook
        return $this;    	
    }

    public function hookIntoSalesOrderSaveBefore($observer)
    {
        //$event = $observer->getEvent();
        //echo 'Inside hookIntoSalesOrderSaveBefore observer...'; exit;
        //Implement the "sales_order_save_before" hook
        return $this;    	
    }     
    
    public function hookIntoSalesOrderSaveAfter($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo 'Inside hookIntoSalesOrderSaveAfter observer...'; exit;
        //Implement the "sales_order_save_after" hook
        return $this;    	
    } 

    public function hookIntoCatalogProductDeleteBefore($observer)
    {
        $product = $observer->getEvent()->getProduct();
        //echo 'Inside hookIntoCatalogProductDeleteBefore observer...'; exit;
        //Implement the "catalog_product_delete_before" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogruleBeforeApply($observer)
    {
        //$event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogruleBeforeApply observer...'; exit;
        //Implement the "catalogrule_before_apply" hook
        return $this;    	
    }  

    public function hookIntoCatalogruleAfterApply($observer)
    {
        //$event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogruleAfterApply observer...'; exit;
        //Implement the "catalogrule_after_apply" hook
        return $this;    	
    }    
    
    public function hookIntoCatalogProductSaveAfter($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogProductSaveAfter observer...'; exit;
        //Implement the "catalog_product_save_after" hook
        return $this;    	
    }   
	
    public function hookIntoCatalogProductStatusUpdate($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogProductStatusUpdate observer...'; exit;
        //Implement the "catalog_product_status_update" hook
        return $this;    	
    }

    public function hookIntoCatalogEntityAttributeSaveAfter($observer)
    {
        //$event = $observer->getEvent();
        
        //Implement the "catalog_entity_attribute_save_after" hook
        return $this;    	
    }
    
    public function hookIntoCatalogProductDeleteAfterDone($observer)
    {
        $product = $observer->getEvent()->getProduct();
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogProductDeleteAfterDone observer...'; exit;
        //Implement the "catalog_product_delete_after_done" hook
        return $this;    	
    }
    
    public function hookIntoCustomerLogin($observer)
    {
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCustomerLogin observer...'; exit;
        //Implement the "customer_login" hook
        return $this;    	
    }       

    public function hookIntoCustomerLogout($observer)
    {
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCustomerLogout observer...'; exit;
        //Implement the "customer_logout" hook
        return $this;    	
    }

    public function hookIntoSalesQuoteSaveAfter($observer)
    {
        $event = $observer->getEvent();
        //echo 'Inside hookIntoSalesQuoteSaveAfter observer...'; exit;
        //Implement the "sales_quote_save_after" hook
        return $this;    	
    }

    public function hookIntoCatalogProductCollectionLoadAfter($observer)
    {
        $event = $observer->getEvent();
        //echo 'Inside hookIntoCatalogProductCollectionLoadAfter observer...'; exit;
        //Implement the "catalog_product_collection_load_after" hook
        return $this;    	
    }
    
}