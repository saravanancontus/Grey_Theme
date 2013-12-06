<?php
class Apptha_Adddeals_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    
			
		$this->loadLayout();     
		$this->renderLayout();
    }
    
    
    
public function uploadfileAction()
    {
    	
    	 $path = Mage::getBaseDir().DS.'media'.DS. 'catalog_temp';  //desitnation directory
    	 $destination_url =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). 'catalog_temp/';     
         $fname = $_FILES['uploadfile']['name']; //file name
         $uploader = new Varien_File_Uploader('uploadfile'); //load class
         $uploader->setAllowRenameFiles(true);
    	 $uploader->save($path,$fname);
    	 echo $destination_url.$uploader->getUploadedFileName();
    	 exit;
    }
    
    public function uploadVideoAction()
    {
    if (isset($_FILES['uploadVideo']['name']) && $_FILES['uploadVideo']['name'] != '') {
					

						/* Starting upload */
						$uploader = new Varien_File_Uploader('uploadVideo');

						// Any extention would work
						$uploader->setAllowedExtensions(array('mp3', 'MP3', 'flv', 'FLV', 'mp4', 'MP4', 'm4v', 'M4V', 'M4A', 'm4a', 'MOV', 'mov', 'mp4v', 'Mp4v', 'F4V', 'f4v'));
						$uploader->setAllowRenameFiles(true);

						// Set the file upload mode
						// false -> get the file directly in the specified folder
						// true -> get the file in the product like folders
						//	(file.jpg will go in something like /media/f/i/file.jpg)
						$uploader->setFilesDispersion(false);

						// We set media as the upload dir
						$path = Mage::getBaseDir('media') . '/productVideos/video/' . DS;
						$uploader->save($path, $_FILES['uploadVideo']['name']);
					//this way the name is saved in DB
					 $videoname = 'productVideos/video/' . $uploader->getUploadedFileName();
					 echo $videoname; exit;
					
				}
    }
				
			public function uploadVideoImageAction()
			{
				
				if (isset($_FILES['thumbImage']['name']) && $_FILES['thumbImage']['name'] != '') {
            

                /* Starting upload */
                $uploader = new Varien_File_Uploader('thumbImage');
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $path = Mage::getBaseDir('media') . '/productVideos/thumb' . DS;
                $url = strip_tags($_FILES['thumbImage']['name']);
                $uploader->save($path, $url);
                 $videothumbname = '/productVideos/thumb/' . $uploader->getUploadedFileName();
               echo $thumbname = $videothumbname.$url;
               exit;
                
            } 
            
            
            
          
            
            
            
            
				
			}
				
				
    
    
    
}