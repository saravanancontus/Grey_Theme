<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */

/*
 * Contus Support Pvt Ltd.
 * created by Vasanth on nov 02 2010.
 * vasanth@contus.in
 * In this page we can add edit modify the videos.
 */
?>
<?php

class Contus_Videoupload_Adminhtml_VideouploadController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('catalog')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Product Video Manager'), Mage::helper('adminhtml')->__('Product Video Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('videoupload/videoupload')->load($id);

        if ($model->getvideoType() == '1') {
            $model['video_name'] = $model->getVideoname();
        }

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {

                $model->setData($data);
            }

            Mage::register('videoupload_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('videoupload/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('videoupload/adminhtml_videoupload_edit'))
                    ->_addLeft($this->getLayout()->createBlock('videoupload/adminhtml_videoupload_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('videoupload')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
       
        if ($data = $this->getRequest()->getPost()) {

                    
            if($data['video_type'] == 1){

                $ytb_pattern = "@youtube.com\/watch\?v=([0-9a-zA-Z_-]*)@i";

            if (preg_match($ytb_pattern, stripslashes($data['video_name']), $match)) {

                /* Get youtube video details ,preview image, thumb image etc */

               $youtube_data = Mage::getModel('videoupload/videoupload')->hd_GetSingleYoutubeVideo($match[1]);

                if ($youtube_data) {

                    $data['thumname'] = $youtube_data['thumbnail_url'];
                }
            } 
       }
            
            $models = Mage::getModel('catalog/product');
            $_product = $models->load($data['entity_id']);
            $products = $_product->getName();
            $data['product'] = $products;

            /* if it is an url(videoname) */
            if ($data['video_name'] != '') {
                $data['videoname'] = $data['video_name'];
            }


            if (isset($_FILES['videoname']['name']) && $_FILES['videoname']['name'] != '') {
                try {

                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('videoname');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('mp3', 'MP3', 'flv', 'FLV', 'mp4', 'MP4', 'm4v', 'M4V', 'M4A', 'm4a', 'MOV', 'mov', 'mp4v', 'Mp4v', 'F4V', 'f4v'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . '/productVideos/video/' . DS;
                    $product = $_REQUEST['entity_id'];
                    $uploader->save($path, $_FILES['videoname']['name']);
                } catch (Exception $e) {

                }

                //this way the name is saved in DB
                $data['videoname'] = 'productVideos/video/' . $_FILES['videoname']['name'];
            }
        }

        if (isset($_FILES['thumname']['name']) && $_FILES['thumname']['name'] != '') {
            try {

                /* Starting upload */
                $uploader = new Varien_File_Uploader('thumname');

                // Any extention would work
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);

                // Set the file upload mode
                // false -> get the file directly in the specified folder
                // true -> get the file in the product like folders
                //	(file.jpg will go in something like /media/f/i/file.jpg)
                $uploader->setFilesDispersion(false);

                // We set media as the upload dir
                $path = Mage::getBaseDir('media') . '/productVideos/thumb' . DS;
                $product = $_REQUEST['entity_id'];
                $url = strip_tags($_FILES['thumname']['name']);
                $uploader->save($path, $url);
            } catch (Exception $e) {

            }

            //this way the name is saved in DB
            $data['thumname'] = '/productVideos/thumb/' . $_FILES['thumname']['name'];
        }
        if ($this->getRequest()->getParam('id')) {
            //if edit the video details, delete old videos from media folder
            if ($data['thumname'] != '') {
                $videoupload = Mage::getModel('videoupload/videoupload')->load($this->getRequest()->getParam('id'));
                $thumbPath = Mage::getBaseDir('media') . DS;
                $thumbName = $videoupload->getThumname();
                unlink($thumbPath . $thumbName);
            }
        }
        if ($this->getRequest()->getParam('id')) {
            if ($data['videoname'] != '' || $data['video_name'] != '') {
                $videoupload = Mage::getModel('videoupload/videoupload')->load($this->getRequest()->getParam('id'));
                $videoPath = Mage::getBaseDir('media') . DS;
                $videoName = $videoupload->getVideoname();
                unlink($videoPath . $videoName);
            }
        }
        $model = Mage::getModel('videoupload/videoupload');

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
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('videoupload')->__('Item was successfully saved'));
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

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('videoupload')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('videoupload/videoupload');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                //delete videos from media folder
                $videoName = $model->getVideoname();
                $thumbName = $model->getThumname();
                $videoPath = Mage::getBaseDir('media') . '/productVideos/video/' . DS;
                $thumbPath = Mage::getBaseDir('media') . '/productVideos/thumb' . DS;
                unlink($videoPath . $videoName);
                unlink($thumbPath . $thumbName);
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
        $videouploadIds = $this->getRequest()->getParam('videoupload');
        if (!is_array($videouploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($videouploadIds as $videouploadId) {
                    $videoupload = Mage::getModel('videoupload/videoupload')->load($videouploadId);
                    $videoupload->delete();
                    //delete videos from media folder
                    $videoName = $videoupload->getVideoname();
                    $thumbName = $videoupload->getThumname();
                    $videoPath = Mage::getBaseDir('media') . DS;
                    $thumbPath = Mage::getBaseDir('media') . DS;
                    $thumbPath . $thumbName;
                    unlink($videoPath . $videoName);
                    unlink($thumbPath . $thumbName);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($videouploadIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $videouploadIds = $this->getRequest()->getParam('videoupload');
        if (!is_array($videouploadIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($videouploadIds as $videouploadId) {
                    $videoupload = Mage::getSingleton('videoupload/videoupload')
                                    ->load($videouploadId)
                                    ->setStatus($this->getRequest()->getParam('status'))
                                    ->setIsMassupdate(true)
                                    ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($videouploadIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'videoupload.csv';
        $content = $this->getLayout()->createBlock('videoupload/adminhtml_videoupload_grid')
                        ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'videoupload.xml';
        $content = $this->getLayout()->createBlock('videoupload/adminhtml_videoupload_grid')
                        ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }



}