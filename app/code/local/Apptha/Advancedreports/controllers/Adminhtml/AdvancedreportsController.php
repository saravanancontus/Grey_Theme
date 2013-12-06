<?php

class Apptha_Advancedreports_Adminhtml_AdvancedreportsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			// ->_setActiveMenu('advancedreports/items')
                        ->_setActiveMenu('report/advancedreports')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('advancedreports/advancedreports')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('advancedreports_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('advancedreports/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit'))
				->_addLeft($this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedreports')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('advancedreports/advancedreports');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('advancedreports')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('advancedreports')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('advancedreports/advancedreports');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $advancedreportsIds = $this->getRequest()->getParam('advancedreports');
        if(!is_array($advancedreportsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($advancedreportsIds as $advancedreportsId) {
                    $advancedreports = Mage::getModel('advancedreports/advancedreports')->load($advancedreportsId);
                    $advancedreports->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($advancedreportsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $advancedreportsIds = $this->getRequest()->getParam('advancedreports');
        if(!is_array($advancedreportsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($advancedreportsIds as $advancedreportsId) {
                    $advancedreports = Mage::getSingleton('advancedreports/advancedreports')
                        ->load($advancedreportsId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($advancedreportsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'advancedreports.csv';
        $content    = $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'advancedreports.xml';
        $content    = $this->getLayout()->createBlock('advancedreports/adminhtml_advancedreports_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    
public function advancedReportsAction()
{	    

$from=$this->getRequest()->getParam('from');
$to=$this->getRequest()->getParam('to'); 
$report=$this->getRequest()->getParam('report'); 

 $contentType='application/octet-stream';

switch ($report) {

case "transactions_csv":


    $transaction_option_value=$this->getRequest()->getParam('transaction_option_value');

    
            // transactions csv
            
        	/*
            $transactions_status = array('complete', 'processing');
            $orderTotals = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('status', array('in' => $transactions_status))
            ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
            */
    
$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$orderTotals = Mage::getResourceModel('sales/order_grid_collection')
               ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
               ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));
$salesFlatOrder = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_order';
$orderTotals->getSelect()->join(array('sales_flat_order' => $salesFlatOrder),"(sales_flat_order.entity_id=main_table.entity_id )",array('sales_flat_order.base_tax_amount','sales_flat_order.base_shipping_amount','sales_flat_order.total_qty_ordered'));

    
//$fileName="transactions.csv";
$fileName=$this->__('transactions').'.csv';

$content='"'.$this->__('S No').'","'.$this->__('Transaction').'","'.$this->__('Revenue').'","'.$this->__('Tax').'","'.$this->__('Shipping').'","'.$this->__('Quantity').'"'."\n";
//$content='"S No","Transaction","Revenue","Tax","Shipping","Quantity"'."\n";
$exportsno_csv=1;
$transactions_revenue_value=0;
foreach ($orderTotals as $value)    { 

     if($transaction_option_value == 0)
     {
     $transactions_revenue_value = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol() . round($value->getBaseGrandTotal(), 2);
     }
     else
     {
     $transactions_revenue_value = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol() . round($value->getBaseGrandTotal() - ( $value->getBaseShippingAmount() + $value->getBaseTaxAmount()), 2);
     }

$content.='"'.$exportsno_csv.'"';
$content.=',"'.$value->getIncrementId().'"';
$content.=',"'.$transactions_revenue_value.'"';
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value->getBaseTaxAmount(), 2).'"';
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value->getBaseShippingAmount(), 2).'"';
$content.=',"'.round($value->getTotalQtyOrdered()).'"'."\n";

$exportsno_csv=$exportsno_csv+1;
}            
break;

case "product_csv":
// product csv

$product_option_value=$this->getRequest()->getParam('product_option_value');

/*
$orderTotals = Mage::getModel('sales/order_item')->getCollection()
               ->addAttributeToFilter('main_table.created_at', array('from' => $from, 'to' => $to));

$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', 'cp.status');

//$orderTotals->addAttributeToFilter('cp.created_at', array('from' => $from, 'to' => $to));

*/

$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$orderTotals = Mage::getModel('sales/order_item')->getCollection();
//$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order_grid')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', 'cp.created_at as sfog_created_at');
$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order_grid')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', array('cp.created_at as sfog_created_at'));
$orderTotals->addAttributeToFilter('cp.created_at', array('from' => $db_from, 'to' => $db_to));


$obj = Mage::getModel('catalog/product');

$product_report = array();


$qty = array();
$qty_month = array();
$qty_week = array();

foreach ($orderTotals as $value) {
// For table data
    $_product = $obj->load($value->getProductId());

 $_product_name = $_product->getName();
//  $_product_name=$value->getName();



    if (empty($product_report[$_product_name]['name'])) {
        $product_report[$_product_name]['name'] = $_product->getName();
      //    $product_report[$_product_name]['name'] = $value->getName();
    }


   if (array_key_exists($_product_name, $product_report)) {
        $product_report[$_product_name]['qty'] = $product_report[$_product_name]['qty'] + round($value->getQtyOrdered());
        $product_report[$_product_name]['price'] =  $product_report[$_product_name]['price'] +  ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - $value->getBaseDiscountAmount();
        $product_report[$_product_name]['price_net'] = $product_report[$_product_name]['price_net'] + ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - ($value->getBaseDiscountAmount() + $value->getBaseTaxAmount());
      //    $product_report[$_product_name]['price'] =  $product_report[$_product_name]['price'] +   $value->getRowTotal();
      //    $product_report[$_product_name]['price_net'] = $product_report[$_product_name]['price_net'] + ( $value->getRowTotal() - $value->getBaseTaxAmount());

        $product_report[$_product_name]['uni_qty'] = $product_report[$_product_name]['uni_qty'] + 1;
    } else {
        $product_report[$_product_name]['qty'] = round($value->getQtyOrdered());
        $product_report[$_product_name]['price'] = ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - $value->getBaseDiscountAmount();
        $product_report[$_product_name]['price_net'] = ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - ( $value->getBaseDiscountAmount()  + $value->getBaseTaxAmount());
       //  $product_report[$_product_name]['price'] = $value->getRowTotal();
       //  $product_report[$_product_name]['price_net'] =  $value->getRowTotal() - $value->getBaseTaxAmount();
        $product_report[$_product_name]['uni_qty'] = 1;
    }

}
$fileName=$this->__('product').'.csv';
//$fileName="product.csv";
$content='"'.$this->__('S No').'","'.$this->__('Product').'","'.$this->__('Quantity').'","'.$this->__('Unique Purchases').'","'.$this->__('Product Revenue').'","'.$this->__('Average Price').'","'.$this->__('Average QTY').'"'."\n";
//$content='"S No","Product","Quantity","Unique Purchases","Product Revenue","Average Price","Average QTY"'."\n";
$exportsno_csv=1;
foreach ($product_report as $value)    {

$content.='"'.$exportsno_csv.'"';
$content.=',"'.$value['name'].'"';
$content.=',"'.$value['qty'].'"';
$content.=',"'.$value['uni_qty'].'"';
if($product_option_value==0)
{
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price'], 2).'"';
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price']/$value['qty'], 2).'"';
}
else
{
    $content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price_net'], 2).'"';
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price_net']/$value['qty'], 2).'"';
    
}
$content.=',"'.round($value['qty']/$value['uni_qty'],2).'"'."\n";
$exportsno_csv=$exportsno_csv+1;
}
break;


case "sales_csv":

$revenue_option_value=$this->getRequest()->getParam('revenue_option_value');

/*
        	$transactions_status = array('complete', 'processing');
            $orderTotals = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('status', array('in' => $transactions_status))
            ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
*/


$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$orderTotals = Mage::getResourceModel('sales/order_grid_collection')
               ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
               ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));
$salesFlatOrder = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_order';
$orderTotals->getSelect()->join(array('sales_flat_order' => $salesFlatOrder),"(sales_flat_order.entity_id=main_table.entity_id )",array('sales_flat_order.base_tax_amount','sales_flat_order.base_shipping_amount'));

$revenue = array();
$revenue_net = array();
            

foreach ($orderTotals as $order) {

// Day wise gross
     $date_r = Mage::getModel('core/date')->date(null,strtotime($order->getCreatedAt()));
    $date_r = date('l, F j, Y', strtotime($date_r));
    if (array_key_exists($date_r, $revenue)) {
        $revenue[$date_r] = $revenue[$date_r] + $order->getBaseGrandTotal();
    } else {
        $revenue[$date_r] = $order->getBaseGrandTotal();
    }

    // Day wise net
    $date_r = Mage::getModel('core/date')->date(null,strtotime($order->getCreatedAt()));
    $date_r = date('l, F j, Y', strtotime($date_r));

if (array_key_exists($date_r, $revenue_net)) {
        $revenue_net[$date_r] = $revenue_net[$date_r] + $order->getBaseGrandTotal() - (   $order->getBaseShippingAmount() + $order->getBaseTaxAmount());
    } else {
        $revenue_net[$date_r] = $order->getBaseGrandTotal() - (  $order->getBaseShippingAmount() + $order->getBaseTaxAmount());

    }
}
     $total_revenue_value=0;
     $revenue_table_value=array();

     if($revenue_option_value == 0)
     {
     $revenue_table_value=$revenue;
     $total_revenue_value = array_sum($revenue);  
     }
     else
     {
     $revenue_table_value=$revenue_net;
     $total_revenue_value= array_sum($revenue_net);
     }



//$fileName="sales.csv";
$fileName=$this->__('sales').'.csv';
$content='"'.$this->__('S No').'","'.$this->__('Date').'","'.$this->__('Revenue').'","'.$this->__('Percent %').'"'."\n";
//$content='"S No","Date","Revenue","Percent"'."\n";
$exportsno_csv=1;
foreach ($revenue_table_value as $key => $value)    {
$content.='"'.$exportsno_csv.'"';
$content.=',"'.Date('F j, Y', strtotime($key)).'"';
$content.=',"'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value, 2).'"';
$content.=',"'.round(($value / $total_revenue_value * 100), 2).' %"'."\n";

$exportsno_csv=$exportsno_csv+1;
}

break;



case "timetopurchase_csv":

  //  timetopurchase csv

/*
$carts      = Mage::getModel('sales/quote')->getCollection()
              ->addFieldToFilter('main_table.reserved_order_id',array('neq' => 'NULL' ))
              ->addFieldToFilter('main_table.created_at', array('from' => $from, 'to' => $to));

$carts->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order')),'cp.increment_id = main_table.reserved_order_id AND cp.status IN ("complete","processing")','cp.status');
//$carts->addFieldToFilter('cp.created_at', array('from' => $from, 'to' => $to));
*/
	

$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$carts = Mage::getResourceModel('sales/order_grid_collection')
          ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
          ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));

$salesFlatQuote = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_quote';
$carts->getSelect()->join(array('sales_flat_quote' => $salesFlatQuote),"(sales_flat_quote.reserved_order_id=main_table.increment_id)",array('sales_flat_quote.created_at as sfq_created_at','sales_flat_quote.updated_at as sfq_updated_at'));
//$carts->getSelect()->join(array('sales_flat_quote' => $salesFlatQuote),"(sales_flat_quote.entity_id=main_table.entity_id AND main_table.status IN ('complete','processing') AND main_table.created_at > '".$from."' and  main_table.created_at < '".$to."' and sales_flat_quote.reserved_order_id IS NOT NULL)",array('sales_flat_quote.created_at as sfq_created_at','sales_flat_quote.updated_at as sfq_updated_at'));

$timetopurchase = array();

            foreach ($carts as $value)
            {
            $date_u = Mage::getModel('core/date')->date(null,strtotime($value->getSfqUpdatedAt()));
            $date_c = Mage::getModel('core/date')->date(null,strtotime($value->getSfqCreatedAt()));
            $datediff= abs(strtotime($date_u) - strtotime($date_c));
            $nodays= intval(floor($datediff/86400));

           if (array_key_exists($nodays, $timetopurchase)) {
           $timetopurchase[$nodays] = $timetopurchase[$nodays] + 1;
           } else {
           $timetopurchase[$nodays] = 1;
           }
           }


       $total_timetopurchase=array_sum($timetopurchase);

       ksort($timetopurchase);
    

//$fileName="timetopurchase.csv";
$fileName=$this->__('time_to_purchase').'.csv';

//$content='"'.$this->__('S No').'","'.$this->__('Days to Transaction').'","'.$this->__('Transactions').'","'.$this->__('Percentage of total %').'"'."\n";
$content='"'.$this->__('Days to Transaction').'","'.$this->__('Transactions').'","'.$this->__('Percentage of total %').'"'."\n";
//$content='"S No","Days to Transaction","Transactions","Percentage of total"'."\n";
//$exportsno_csv=1;
foreach ($timetopurchase as $key=>$value)    {
//$content.='"'.$exportsno_csv.'"';
//$content.=',"'.$key.'"';
$content.='"'.$key.'"';
$content.=',"'.$value.'"';
$content.=',"'.round(($value / $total_timetopurchase * 100), 2).' %"'."\n";

//$exportsno_csv=$exportsno_csv+1;
}
break;

case "transactions_xml":
// transactions xml
 $transaction_option_value=$this->getRequest()->getParam('transaction_option_value');

 
            /*
            $transactions_status = array('complete', 'processing');
            $orderTotals = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('status', array('in' => $transactions_status))
            ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
            */
 
 $db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$orderTotals = Mage::getResourceModel('sales/order_grid_collection')
               ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
               ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));
$salesFlatOrder = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_order';
$orderTotals->getSelect()->join(array('sales_flat_order' => $salesFlatOrder),"(sales_flat_order.entity_id=main_table.entity_id )",array('sales_flat_order.base_tax_amount','sales_flat_order.base_shipping_amount','sales_flat_order.total_qty_ordered'));


$fileName=$this->__('transactions').'.xml';
//$fileName="transactions.xml";
$content='<?xml version="1.0"?>';

$content.='<transactions><header><SNo>S No</SNo><Transaction>Transaction</Transaction><Revenue>Revenue</Revenue><Tax>Tax</Tax><Shipping>Shipping</Shipping><Quantity>Quantity</Quantity></header>';

$exportsno_csv=1;
$transactions_revenue_value=0;	
foreach ($orderTotals as $value)    {

 if($transaction_option_value == 0)
     {
     $transactions_revenue_value = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol() . round($value->getBaseGrandTotal(), 2);
     }
     else
     {
     $transactions_revenue_value = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol() . round($value->getBaseGrandTotal() - ( $value->getBaseShippingAmount() + $value->getBaseTaxAmount()), 2);
     }
	
$content.='<Data><SNo>'.$exportsno_csv.'</SNo>';
$content.='<Transaction>'.$value->getIncrementId().'</Transaction>';
$content.='<Revenue>'.$transactions_revenue_value.'</Revenue>';
$content.='<Tax>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value->getBaseTaxAmount(), 2).'</Tax>';
$content.='<Shipping>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value->getBaseShippingAmount(), 2).'</Shipping>';
$content.='<Quantity>'.round($value->getTotalQtyOrdered()).'</Quantity></Data>';

$exportsno_csv=$exportsno_csv+1;
} 

$content.='</transactions>';

break;



case "product_xml":
// product xml


$product_option_value=$this->getRequest()->getParam('product_option_value');

/*
$orderTotals = Mage::getModel('sales/order_item')->getCollection()
               ->addAttributeToFilter('main_table.created_at', array('from' => $from, 'to' => $to));

$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', 'cp.status');

//$orderTotals->addAttributeToFilter('cp.created_at', array('from' => $from, 'to' => $to));


*/

$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$orderTotals = Mage::getModel('sales/order_item')->getCollection();
//$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order_grid')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', 'cp.created_at as sfog_created_at');
$orderTotals->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order_grid')), 'cp.entity_id = main_table.order_id AND cp.status IN ("complete","processing")', array('cp.created_at as sfog_created_at'));
$orderTotals->addAttributeToFilter('cp.created_at', array('from' => $db_from, 'to' => $db_to));


$obj = Mage::getModel('catalog/product');
$product_report = array();

$qty = array();
$qty_month = array();
$qty_week = array();

foreach ($orderTotals as $value) {
// For table data

   $_product = $obj->load($value->getProductId());

    $_product_name = $_product->getName();
//  $_product_name=$value->getName();

    if (empty($product_report[$_product_name]['name'])) {
        $product_report[$_product_name]['name'] = $_product->getName();
       //   $product_report[$_product_name]['name'] = $value->getName();
    }



   if (array_key_exists($_product_name, $product_report)) {
        $product_report[$_product_name]['qty'] = $product_report[$_product_name]['qty'] + round($value->getQtyOrdered());
        $product_report[$_product_name]['price'] =  $product_report[$_product_name]['price'] +  ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - $value->getBaseDiscountAmount();
        $product_report[$_product_name]['price_net'] = $product_report[$_product_name]['price_net'] + ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - ($value->getBaseDiscountAmount() + $value->getBaseTaxAmount());
       //   $product_report[$_product_name]['price'] =  $product_report[$_product_name]['price'] +   $value->getRowTotal();
       //   $product_report[$_product_name]['price_net'] = $product_report[$_product_name]['price_net'] + ( $value->getRowTotal() - $value->getBaseTaxAmount());

        $product_report[$_product_name]['uni_qty'] = $product_report[$_product_name]['uni_qty'] + 1;
    } else {
        $product_report[$_product_name]['qty'] = round($value->getQtyOrdered());
        $product_report[$_product_name]['price'] = ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - $value->getBaseDiscountAmount();
        $product_report[$_product_name]['price_net'] = ($value->getBaseOriginalPrice() * $value->getQtyOrdered()) - ( $value->getBaseDiscountAmount()  + $value->getBaseTaxAmount());
       //  $product_report[$_product_name]['price'] = $value->getRowTotal();
       //  $product_report[$_product_name]['price_net'] =  $value->getRowTotal() - $value->getBaseTaxAmount();
        $product_report[$_product_name]['uni_qty'] = 1;
    }

}
$fileName=$this->__('product').'.xml';
//$fileName="product.xml";
$content='<?xml version="1.0"?>';
$content.='<product><header><SNO>S No</SNO><Product>Product</Product><Quantity>Quantity</Quantity><UniquePurchases>Unique Purchases</UniquePurchases><ProductRevenue>Product Revenue</ProductRevenue><AveragePrice>Average Price</AveragePrice><AverageQTY>Average QTY</AverageQTY></header>';

$exportsno_csv=1;
foreach ($product_report as $value)    {

$content.='<Data><SNO>'.$exportsno_csv.'</SNO>';
$content.='<Product>'.$value['name'].'</Product>';
$content.='<Quantity>'.$value['qty'].'</Quantity>';
$content.='<UniquePurchases>'.$value['uni_qty'].'</UniquePurchases>';

if($product_option_value==0)
{
$content.='<ProductRevenue>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price'], 2).'</ProductRevenue>';
$content.='<AveragePrice>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price']/$value['qty'], 2).'</AveragePrice>';
}
else
{
$content.='<ProductRevenue>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price_net'], 2).'</ProductRevenue>';
$content.='<AveragePrice>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price_net']/$value['qty'], 2).'</AveragePrice>';   
}

$content.='<ProductRevenue>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price'], 2).'</ProductRevenue>';
$content.='<AveragePrice>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value['price']/$value['qty'], 2).'</AveragePrice>';
$content.='<AverageQTY>'.round($value['qty']/$value['uni_qty'],2).'</AverageQTY></Data>';

$exportsno_csv=$exportsno_csv+1;
}

$content.='</product>';
break;


case "sales_xml":

// sales xml

$revenue_option_value=$this->getRequest()->getParam('revenue_option_value');

        	/*
            $transactions_status = array('complete', 'processing');
            $orderTotals = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('status', array('in' => $transactions_status))
            ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to));
            */



$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$orderTotals = Mage::getResourceModel('sales/order_grid_collection')
               ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
               ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));
$salesFlatOrder = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_order';
$orderTotals->getSelect()->join(array('sales_flat_order' => $salesFlatOrder),"(sales_flat_order.entity_id=main_table.entity_id )",array('sales_flat_order.base_tax_amount','sales_flat_order.base_shipping_amount'));

$revenue = array();
$revenue_net = array();
            

foreach ($orderTotals as $order) {

// Day wise gross
    $date_r = Mage::getModel('core/date')->date(null,strtotime($order->getCreatedAt()));
    $date_r = date('l, F j, Y', strtotime($date_r));
    if (array_key_exists($date_r, $revenue)) {
        $revenue[$date_r] = $revenue[$date_r] + $order->getBaseGrandTotal();
    } else {
        $revenue[$date_r] = $order->getBaseGrandTotal();
    }

    // Day wise net
    $date_r = Mage::getModel('core/date')->date(null,strtotime($order->getCreatedAt()));
    $date_r = date('l, F j, Y', strtotime($date_r));

if (array_key_exists($date_r, $revenue_net)) {
        $revenue_net[$date_r] = $revenue_net[$date_r] + $order->getBaseGrandTotal() - (   $order->getBaseShippingAmount() + $order->getBaseTaxAmount() );
    } else {
        $revenue_net[$date_r] = $order->getBaseGrandTotal() - (  $order->getBaseShippingAmount() + $order->getBaseTaxAmount());

    }
}

     $total_revenue_value=0;
     $revenue_table_value=array();

     if($revenue_option_value == 0)
     {
     $revenue_table_value=$revenue;
     $total_revenue_value = array_sum($revenue);
     }
     else
     {
     $revenue_table_value=$revenue_net;
     $total_revenue_value= array_sum($revenue_net);
     }

$fileName=$this->__('sales').'.xml';
//$fileName="sales.xml";
$content='<?xml version="1.0"?>';
$content.='<sales><header><SNo>S No</SNo><Date>Date</Date><Revenue>Revenue</Revenue><Percent>Percent %</Percent></header>';
//$content.='<sales><header><SNo>S No</SNo><Date>Date</Date><Revenue>Revenue</Revenue><Percent>Percent %</Percent></header>';
$exportsno_csv=1;
foreach ($revenue_table_value as $key => $value)    {
$content.='<Data><SNo>'.$exportsno_csv.'</SNo>';
$content.='<Date>'.Date('F j, Y', strtotime($key)).'</Date>';
$content.='<Revenue>'.Mage::app()->getLocale()->currency(Mage::app()->getStore()->getBaseCurrencyCode())->getSymbol().round($value, 2).'</Revenue>';
$content.='<Percent>'.round(($value / $total_revenue_value * 100), 2).' %</Percent></Data>';

$exportsno_csv=$exportsno_csv+1;
}
$content.='</sales>';
break;


case "timetopurchase_xml":

  //  timetopurchase xml

/*
$carts      = Mage::getModel('sales/quote')->getCollection()
              ->addFieldToFilter('main_table.reserved_order_id',array('neq' => 'NULL' ))
              ->addFieldToFilter('main_table.created_at', array('from' => $from, 'to' => $to));

$carts->getSelect()->joinInner(array('cp' => Mage::getSingleton('core/resource')->getTableName('sales/order')),'cp.increment_id = main_table.reserved_order_id AND cp.status IN ("complete","processing")','cp.status');
//$carts->addFieldToFilter('cp.created_at', array('from' => $from, 'to' => $to));

*/
	

$db_from=Mage::getModel('core/date')->gmtDate(null,strtotime($from));
$db_to=Mage::getModel('core/date')->gmtDate(null,strtotime($to));

$sales_status = array('complete', 'processing');
$carts = Mage::getResourceModel('sales/order_grid_collection')
          ->addAttributeToFilter('main_table.status', array('in' => $sales_status))
          ->addAttributeToFilter('main_table.created_at', array('from' => $db_from, 'to' => $db_to));

$salesFlatQuote = (string)Mage::getConfig()->getTablePrefix() . 'sales_flat_quote';
$carts->getSelect()->join(array('sales_flat_quote' => $salesFlatQuote),"(sales_flat_quote.reserved_order_id=main_table.increment_id)",array('sales_flat_quote.created_at as sfq_created_at','sales_flat_quote.updated_at as sfq_updated_at'));
//$carts->getSelect()->join(array('sales_flat_quote' => $salesFlatQuote),"(sales_flat_quote.entity_id=main_table.entity_id AND main_table.status IN ('complete','processing') AND main_table.created_at > '".$from."' and  main_table.created_at < '".$to."' and sales_flat_quote.reserved_order_id IS NOT NULL)",array('sales_flat_quote.created_at as sfq_created_at','sales_flat_quote.updated_at as sfq_updated_at'));

$timetopurchase = array();

            foreach ($carts as $value)
            {
            $date_u = Mage::getModel('core/date')->date(null,strtotime($value->getSfqUpdatedAt()));
            $date_c = Mage::getModel('core/date')->date(null,strtotime($value->getSfqCreatedAt()));
            $datediff= abs(strtotime($date_u) - strtotime($date_c));
            $nodays= intval(floor($datediff/86400));

           if (array_key_exists($nodays, $timetopurchase)) {
           $timetopurchase[$nodays] = $timetopurchase[$nodays] + 1;
           } else {
           $timetopurchase[$nodays] = 1;
           }
           }


       $total_timetopurchase=array_sum($timetopurchase);

       ksort($timetopurchase);
    

$fileName=$this->__('time_to_purchase').'.xml';
//$fileName="timetopurchase.xml";
$content='<?xml version="1.0"?>';
//$content.='<timetopurchase><header><SNo>S No</SNo><DaystoTransaction>Days to Transaction</DaystoTransaction><Transactions>Transactions</Transactions><Percentageoftotal>Percentage of total %</Percentageoftotal></header>';
$content.='<timetopurchase><header><DaystoTransaction>Days to Transaction</DaystoTransaction><Transactions>Transactions</Transactions><Percentageoftotal>Percentage of total %</Percentageoftotal></header>';
//$exportsno_csv=1;
foreach ($timetopurchase as $key=>$value)    {
//$content.='<Data><SNo>'.$exportsno_csv.'</SNo>';
$content.='<Data>';
$content.='<DaystoTransaction>'.$key.'</DaystoTransaction>';
$content.='<Transactions>'.$value.'</Transactions>';
$content.='<Percentageoftotal>'.round(($value / $total_timetopurchase * 100), 2).'%</Percentageoftotal></Data>';
//$exportsno_csv=$exportsno_csv+1;
}
$content.='</timetopurchase>';
break;
       
        default:
      
    }
  	 
  	    $contentType='application/octet-stream';
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;  	
  

  }
    
}