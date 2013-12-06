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
 * In this page we can include form.phtml in admin side.
 */
?>

<?php

class Contus_Videoupload_Block_Adminhtml_Videoupload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Videoupload/form.phtml');

    }

    public function edit()
    {
        if($this->getRequest()->getParam('id'))
        {
            $id = $this->getRequest()->getParam('id');
            $model  = Mage::getModel('videoupload/videoupload')->load($id);

            if($model->getvideoType() == '1')
            {
                $model['video_name']=$model->getVideoname();
                $model['videoname']= '';
            }
        }
        return $model;
    }
    
}