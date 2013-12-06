<?php

class Apptha_Adddeals_Adminhtml_AdddealsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('adddeals/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

		return $this;
	}

	public function indexAction() {
		$this->_initAction()
		->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('catalog/product')->load($id);


		//print_r($model);exit;

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('adddeals_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('adddeals/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('adddeals/adminhtml_adddeals_edit'))
			->_addLeft($this->getLayout()->createBlock('adddeals/adminhtml_adddeals_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adddeals')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
			
		if ($data = $this->getRequest()->getPost()) {

			try {
				$currentWebsiteId  = Mage::app()->getStore(true)->getWebsite()->getId();
					
				Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			 $storename = Mage::app()->getStore()->getId();
			 $adminuser = Mage::getSingleton('admin/session')->getUser();
			 $roleId = implode('', $adminuser->getRoles());
			 $userId = $adminuser->getId();

			 if($roleId != '1')
			 {
			 	$merchant = $userId;
			 }else {
			 	$merchant = '';
			 }
			 $productName =  $data['dealtitle'];
			 $productSubTitle = $data['dealsubtitle'];
			 $productPrice = $data['price'];
			 $productSpecialPrice = $data['specialprice'];
			 $productStartDate = $data['startdate'];
			 $productEndDate = $data['enddate'];
			 $productStartTime = $data['dealstarttime'];
			 $productEndTime = $data['dealendtime'];
			 $couponvalidTill = $data['couponvalidtil'];
			 $productInvertory = $data['inventory'];
			 $productStatus = $data['status'];
			 $dealCity = $data['dealcity'];
			 $productDescription = $data['dealdescription'];
			 $dealFinePrints = $data['fineprints'];
			 $highlights =  $data['highlights'];
			 $companyAddress = $data['companyaddress'] ;
			 $map =  $data['map'];
			 $merchatEmail = $data['merchantemail'];
			 $merchantPhone = $data['merchantphone'];
			 $productSku =  $data['productsku']; ;
			 $dealTarget = $data['dealtarget'];
			 $featuredDeal = $data['featureddeal'];
			 $travelDeal = $data['traveldeal'];
			 $videoname = $data['videoname'];
			 $videotype = $data['videotype'];
			 $thumname =  $data['thumname'];
			 $category_id = $data['dealCatIds'];
			 $product_video_url = $data['url_video'];
			 $productEditId = $data['productEditId'];
			 $imagefield_selected = $data['image_name'];
			  
			 $tax_class_id = $data['tax_class_id'];
			 $meta_keywords = $data['meta_keywords'];
			 $meta_title = $data['meta_title'];
			 $meta_description = $data['meta_description'];
			 $weight = $data['weight'];
			 $video_status = $data['video_status'];
			
			 $website = $data['dealwebsite'];
			 	
			 $customoption_main_title = $data['customoption_main_title'];

			 if($travelDeal == 0)
			 {
			 	$travelDeal = 1;
			 }
			 else if($travelDeal == 1)
			 {
			 	$travelDeal = 0;
			 }
			 
			 if($featuredDeal == 0)
			 {
			 	$featuredDeal = 1;
			 }else if($featuredDeal == 1)
			 {
			 	$featuredDeal = 0;
			 }

			 if($customoption_main_title != '')
			 {
			 	$travelDeal = 1;
			 	$custom_title = $data['custom_title'];
			 	$customoptions_price = $data['customoptions'];
			 	$prod_sku = $data['prod_sku'];
			 	$custom_title = rtrim($custom_title,',');
			 	$customoptions_price = rtrim($customoptions_price,',');
			 	$prod_sku = rtrim($prod_sku,',');
			 	$optionData[] = $this->setCustomOption($custom_title, $customoptions_price, $prod_sku , $customoption_main_title,"drop_down");

			 }
			 	
			 	
			 	
			 if($video_status == '' || $video_status == 1)
			 {
			 	$video_status = 0;
			 }else if($video_status == 0)
			 {
			 	$video_status = 1;
			 }
			 if( $weight == '')
			 {
			 	$weight = 0;
			 }

			 	
			 if($productInvertory != '' || $productInvertory != 0)
			 {
			 	$productInvertory = $productInvertory;
			 	$isSaleable = 1;
			 	$isstock = 1;
			 }else {

			 	$productInvertory = 0;
			 	$isSaleable = '';
			 	$isstock = 0;
			 }
			 $productStartDate = strtotime($productStartDate);
			 $productEndDate = strtotime($productEndDate);
			 //check whether sku exist or not
			 	
			 if($productEditId == '' )
			 {
			 	$id = Mage::getResourceSingleton('catalog/product')->getIdBySku($productSku);
			 	if ($id){
			 		echo "SKU";
			 		exit;
			 	}
			 }

			 	
			 	

			 if($productEditId == '' )
			 {
                              if($roleId != '1'){
                                 $productStatus = 2;
                             }
			 	$product = Mage::getModel('catalog/product')
			 	->setStoreId(1)
			 	->setAttributeSetId(4)
			 	->setName("$productName")
			 	->setDescription("$productDescription")
			 	->setShortDescription("$productDescription")
			 	->setSku("$productSku")
			 	->setStatus($productStatus)
			 	->setPrice("$productPrice")
			 	->setSpecialPrice("$productSpecialPrice")
			 	->setSpecialFromDate("$productStartDate")
			 	->setSpecialToDate("$productEndDate")
			 	->setStarttime("$productStartTime")
			 	->setTime("$productEndTime")
			 	->setTargetNumber("$dealTarget")
			 	->setEnjoyby("$couponvalidTill")
			 	->setFineprint("$dealFinePrints")
			 	->setHightlight("$highlights")
			 	->setCustomeremail("$merchatEmail")
			 	->setWeight($weight)
			 	->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)//Visibility in both catalog and search
			 	->setCustomersite("$companyAddress")
			 	->setQuestions("$merchantPhone")
			 	->setSitemap("$map")
			 	->setSubtitle("$productSubTitle")
			 	->setCustomerWebsite("$website")
			 	->setMerchant($merchant)

			 	->setFeatureddeal("$featuredDeal")
			 	->setMetaTitle("$meta_title")
			 	->setMetaDescription("$meta_description")

			 	->setMetaKeyword("$meta_keywords")
			 	->setWebsiteIDs(array($currentWebsiteId))
			 	->setTravel("$travelDeal")
			 	->setTaxClassId($tax_class_id)
			 	->setCategoryIds($category_id)
			 	->setDealcity($dealCity)

			 	
			 	->setStockData(array('is_in_stock' => $isstock,'qty' => $productInvertory,'is_saleable' => 1 ))
			 	->setCreatedAt(strtotime('now'))
			 	->setTypeId('dealproduct')
			 	->setCreatedAt(strtotime('now'))
			 	->save();

			 }


			 if($productEditId != '' )
			 {

			 	$product = Mage::getModel('catalog/product')->load($productEditId);

			 	$product ->setName("$productName")
			 	->setDescription("$productDescription")
			 	->setShortDescription("$productDescription")
			 	->setSku("$productSku")
			 	->setStatus($productStatus)
			 	->setPrice("$productPrice")
			 	->setSpecialPrice("$productSpecialPrice")
			 	->setSpecialFromDate("$productStartDate")
			 	->setSpecialToDate("$productEndDate")
			 	->setStarttime("$productStartTime")
			 	->setTime("$productEndTime")
			 	->setTargetNumber("$dealTarget")
			 	->setEnjoyby("$couponvalidTill")
			 	->setFineprint("$dealFinePrints")
			 	->setHightlight("$highlights")
			 	->setCustomeremail("$merchatEmail")
			 	->setWeight($weight)
			 	->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)//Visibility in both catalog and search
			 	->setCustomersite("$companyAddress")
			 	->setQuestions("$merchantPhone")
			 	->setSitemap("$map")
			 	->setSubtitle("$productSubTitle")
			 	->setCustomerWebsite("$website")
			 	->setFeatureddeal("$featuredDeal")
			 	->setMetaTitle("$meta_title")
			 	->setMetaDescription("$meta_description")

			 	->setMetaKeyword("$meta_keywords")
			 	->setWebsiteIDs(array($currentWebsiteId))
			 	->setTravel("$travelDeal")
			 	->setTaxClassId($tax_class_id)
			 	->setCategoryIds($category_id)
			 	->setDealcity($dealCity)
			 	

			 	
			 	->setStockData(array('is_in_stock' => $isstock,'qty' => $productInvertory,'is_saleable' => 1 ))
			 	->setCreatedAt(strtotime('now'))
			 	->setTypeId('dealproduct')
			 	->setCreatedAt(strtotime('now'))
			 	->save();

			 }



				if($data['imagefield'])
				{
					$image_name = rtrim($data['imagefield'],",");
					$imagefield =  explode(',',$image_name);
					$imageCount = count($imagefield);
				}
if(!empty($imagefield_selected))
{
	$imagefield_selected =  explode(',',$imagefield_selected);
	 $base_image  = $imagefield_selected[0];
	 $thumb_image = $imagefield_selected[1];
	 $small_image = $imagefield_selected[2];
}


				$new_product_id = $product ->getId();
				$product = Mage::getModel('catalog/product')->load($new_product_id);

				$product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
				
				if($productEditId == '')
				{


					if($imageCount  != '' )
					{
						
							
						foreach ($imagefield as $imagenew )
						{

  							$value = basename($imagenew);
  							
							if($value == $base_image)
							{

								 $baseImage = "image";
							}else 
							{
								$baseImage = "";
							}
					

							if($value == $thumb_image)
							{
								$thumbImage = "thumbnail";
							}else {
								$thumbImage = "";
							}

							if($value == $small_image)
							{
								$smallImage = "small_image";
							}else {
								$smallImage = "";
							}

							
							$value1 = Mage::getBaseDir('media').DS.'catalog_temp'.DS. $value;
							$product->addImageToMediaGallery ($value1,  array ($baseImage,$smallImage,$thumbImage), true, false);
							
						}
						$product->save();
						
						
					}
				}else if($productEditId != '')
				{


					if($imageCount  != '' )
					{

							
					

						foreach ($imagefield as $imagenew )
						{
							$mediaImageUrl[] = basename($imagenew);

							$path_info = pathinfo($imagenew);

							$pieces = explode("/", $path_info['dirname']);
							if(in_array('catalog_temp',$pieces))
							{
								
							$value = basename($imagenew);

												
							
								$baseImage = "";
							
								
					

							
								
								$thumbImage = "";
							
								
							
								
								$smallImage = "";
							
								
								
								
								
							
								$value1 = Mage::getBaseDir('media').DS.'catalog_temp'.DS. $value;
								$product->addImageToMediaGallery ($value1,  array ($baseImage,$smallImage,$thumbImage), true, false);
							
							}
						}


	$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
						$items = $mediaApi->items($productEditId);
						foreach($items as $item)
						{


							
							 $productImage = basename($item['url']);


							if(in_array("$productImage",$mediaImageUrl) != 1)
							{
								$mediaApi->remove($productEditId, $item['file']);

							}
								
							
							
							
							
								
								
							}
							
								$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
						$items = $mediaApi->items($productEditId);
							
							foreach($items as $item)
						{


							
							 $productImage = basename($item['url']);
					      if($productImage == $base_image)
							{

								$product->setImage($item['file']);
							}
					

							if($productImage == $thumb_image)
							{
								$product->setSmallImage($item['file']);
							}

							if($productImage == $small_image)
							{
								$product->setThumbnail($item['file']);
								
							}
							

						}


$product->save();

					}


				}
					
				Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

				//Custom Options




				if($customoption_main_title != '')
				{


					$product = Mage::getModel("catalog/product")->load($new_product_id);


					if(count($product->getOptions()) == 0){
						foreach ($optionData as $options) {
							foreach ($options as $option) {
								$opt = Mage::getModel('catalog/product_option');
								$opt->setProduct($product);
								$opt->addOption($option);
								$opt->saveOptions();
							}
						}

					}
					if(count($product->getOptions()) != 0){
						foreach ($product->getOptions() as $opt)
						{
							$opt->delete();
						}
							
						foreach ($optionData as $options) {
							foreach ($options as $option) {
								$opt = Mage::getModel('catalog/product_option');
								$opt->setProduct($product);
								$opt->addOption($option);
								$opt->saveOptions();
							}
						}
					}



					//Enabling created options to show in backend as well as frontend.
					$product->setHasOptions(1)->save();
				}






					
				if($videotype != 0)
				{
					//if it is a youtube url
				 if($data['videotype'] == 1){

				 	$ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";

				 	if (preg_match($ytb_pattern, stripslashes($product_video_url), $match)) {

				 		/* Get youtube video details ,preview image, thumb image etc */

				 		$youtube_data = Mage::getModel('adddeals/adddeals')->hd_GetSingleYoutubeVideo($match[1]);

				 		if ($youtube_data) {

				 			$videoThumbPath = $youtube_data['thumbnail_url'];
				 		}
				 	}

				 	$videoname = $product_video_url;
				 	$videotype = 1;
				 }
				 //if it is a youtube url
				 if($data['videotype'] == 2){
				 	$videotype = 0;
				 }

					$tprefix = (string) Mage::getConfig()->getTablePrefix();
					$dealoptions = $tprefix. 'videoupload';
					/** @var $coreResource Mage_Core_Model_Resource */
					$coreResource = Mage::getSingleton('core/resource');
					//** @var $conn Varien_Db_Adapter_Pdo_Mysql */
					$conn = $coreResource->getConnection('core_read');
						
					$select = $conn->select()
					->from($coreResource->getTableName('videoupload/videoupload'), new Zend_Db_Expr('COUNT(*)'))
					->where('entity_id = ?', $new_product_id);

					 $count = $conn->fetchOne($select);
					if($count == '0' )
					{

						 
							
						$conn->insert(
						$coreResource->getTableName($dealoptions),
						array('category_id' => "1", 'entity_id' => "$new_product_id", 'video_id' => 0 , "video_type"=>$videotype , 'videoname'=> "$videoname" , 'title'=> "$productName",'thumname'=> "$videoThumbPath",'product' => "$productName",'status' =>$video_status)
						);

					}else {

					 	$conn->update(
						$coreResource->getTableName('videoupload/videoupload'),
						array('video_id' => 0 , "video_type"=>$videotype , 'videoname'=> "$videoname" , 'title'=> "$productName",'thumname'=> "$videoThumbPath",'product' => "$productName",'status' =>$video_status ),
						array(
					        'entity_id = ?' => $new_product_id
						)
						);

					}

				}
				
					
				



			}
			catch (Mage_Core_Exception $e) {
				
				
			}
			catch (Exception $e) {
				
			}




		}
echo Mage::helper("adminhtml")->getUrl("adddeals/adminhtml_adddeals/index/");
		exit;
	}

	public function deleteAction() {
		if ($id = $this->getRequest()->getParam('id')) {
			$product = Mage::getModel('catalog/product')
			->load($id);
			$sku = $product->getSku();
			try {
				$product->delete();
				$this->_getSession()->addSuccess($this->__('The product has been deleted.'));
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$productIds = $this->getRequest()->getParam('adddeals');


		if (!is_array($productIds)) {
			$this->_getSession()->addError($this->__('Please select product(s).'));
		} else {
			if (!empty($productIds)) {
				try {
					foreach ($productIds as $productId) {
						$product = Mage::getSingleton('catalog/product')->load($productId);
						Mage::dispatchEvent('catalog_controller_product_delete', array('product' => $product));
						$product->delete();
					}
					$this->_getSession()->addSuccess(
					$this->__('Total of %d record(s) have been deleted.', count($productIds))
					);
				} catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}
		$this->_redirect('*/*/index');
	}

	public function massStatusAction()
	{
		$productIds = $this->getRequest()->getParam('adddeals');
		$storeId    = (int)$this->getRequest()->getParam('store', 0);
		$status     = (int)$this->getRequest()->getParam('status');

		try {
			$this->_validateMassStatus($productIds, $status);
			Mage::getSingleton('catalog/product_action')
			->updateAttributes($productIds, array('status' => $status), $storeId);

			$this->_getSession()->addSuccess(
			$this->__('Total of %d record(s) have been updated.', count($productIds))
			);
		}
		catch (Mage_Core_Model_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		} catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		} catch (Exception $e) {
			$this->_getSession()
			->addException($e, $this->__('An error occurred while updating the product(s) status.'));
		}

		$this->_redirect('*/*/index');
	}

	public function exportCsvAction()
	{
		$fileName   = 'adddeals.csv';
		$content    = $this->getLayout()->createBlock('adddeals/adminhtml_adddeals_grid')
		->getCsv();

		$this->_sendUploadResponse($fileName, $content);
	}

	public function exportXmlAction()
	{
		$fileName   = 'adddeals.xml';
		$content    = $this->getLayout()->createBlock('adddeals/adminhtml_adddeals_grid')
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

	public function _validateMassStatus(array $productIds, $status)
	{
		if ($status == Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
			if (!Mage::getModel('catalog/product')->isProductsHasSku($productIds)) {
				throw new Mage_Core_Exception(
				$this->__('Some of the processed products have no SKU value defined. Please fill it prior to performing operations on these products.')
				);
			}
		}
	}
	public function getChildCategoriesAction()
	{


		$CategoryId = $this->getRequest()->getParam('id');
		$Childcategory = Mage::getModel('catalog/category')->load($CategoryId);
		$categorychild = $Childcategory->getChildren();
		if($categorychild != '')
		{
			$categorychildArray = explode( ',' ,$categorychild );
			$i = 0;
			foreach ($categorychildArray as $categoryChildId)
			{
				$category = Mage::getModel('catalog/category')->load($categoryChildId);
				$rootCategoryDetails [$i]['id'] = $category->getId();
				$rootCategoryDetails[$i]['name']= $category->getName();

				$i++;
			}


			$categorychildArray =  json_encode($rootCategoryDetails);
		}else {
			$categorychildArray = "No Subcategories";
		}
			
		print_r($categorychildArray); die;
			


	}

	function setCustomOption($value,$customoptions_price, $sku , $title, $type, $noOption = false)
	{
		$custom_options = array();
		if ($type && $value != "" && $value) {
			$values = explode(',', $value);
			$skus = explode(',', $sku);
			$customoptions_prices = explode(',', $customoptions_price);

			if (count($values)) {
				/**If the custom option has options*/
				if (! $noOption) {
					$is_required = 0;
					$sort_order = 0;
					$custom_options[] = array(
                        'is_delete' => 0 , 
                        'title' => $title , 
                        'previous_group' => '' , 
                        'previous_type' => '' , 
                        'type' => $type , 
                        'is_require' => $is_required , 
                        'sort_order' => $sort_order , 
                        'values' => array()
					);

					for($i = 0; $i < (count($values)) ; $i++)
					{
							

						switch ($type) {
							case 'drop_down':
							case 'radio':
							case 'checkbox':
							case 'multiple':
							default:
								$custom_options[count($custom_options) - 1]['values'][] = array(
                                    'is_delete' => 0 , 'title' => $values[$i] , 'option_type_id' => - 1 , 'price_type' => 'fixed' , 'price' => $customoptions_prices[$i]  , 'sku' => $skus[$i]    , 'sort_order' => ''
                                    );

                                    break;

						}
					}
					return $custom_options;
				}
				/**If the custom option doesn't have options | Case: area and field*/
				else {
					$is_required = 0;
					$sort_order = '';
					$custom_options[] = array(
                        "is_delete" => 0 , "title" => $title , "previous_group" => "text" , "price_type" => 'fixed' , "price" => '' , "type" => $type , "is_required" => $is_required
					);
					return $custom_options;
				}
			}
		}
		return false;

	}

}