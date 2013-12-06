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
 * created by Vasanth on nov 10 2010.
 * vasanth@contus.in
 * In this page used to create config,language,playlist xmls for hdflv player
 */
?>
<?php

class Contus_Videoupload_IndexController extends Mage_Core_Controller_Front_Action {

	public function indexAction() {
		$this->loadLayout();
		$this->renderLayout();
	}

	public function getSkinUrl($file=null, array $params=array()) {
		return Mage::getDesign()->getSkinUrl($file, $params);
	}

	/* Create playlist Xml */

	public function showAction() {
		/* Retrive video Details from videoupload table using property id */

		$pid = $this->getRequest()->getParam('pid');
		$product = Mage::getModel('catalog/product')->load($pid);
		$productName = $product->getName();
		$timage = $product->getImageUrl();
		$targeturl = $product->getProductUrl();
		$_videoCollection = Mage::getModel('videoupload/videoupload');
		$_videoCollection = $_videoCollection->getCollection();
		/* Filter videoCollection using product id and status is enable */
		$_videoCollection->addFieldToFilter('entity_id', Array('eq' => $pid));
		$_videoCollection->addFieldToFilter('status', Array('eq' => '1'));
                                    $pdownload = Mage::getStoreConfig('videoupload/videoupload/download');

		$playxml = '';
		/* Create playlist xml file for hdflv player */
		ob_clean();
		header("content-type: text/xml");
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<playlist autoplay="true" random="false">';
		$current_path = Mage::getBaseURL('media');

		foreach ($_videoCollection as $rows) {
			//$timage = $rows->getThumname();
			/* Check the video type from videoupload table. if video type uploaded */
			if ($rows->getvideoType() == '0') {
				$video = $current_path . $rows->getVideoname();
				$previewimage = $current_path . $rows->getThumname();
			//	$timage = $current_path . $rows->getThumname();
			}
			/* if video type url */ elseif ($rows->getvideoType() == '1') {
			$video = $rows->getVideoname();
			//                $previewimage = $current_path . $rows->getThumname();
			//                $timage = $current_path.$rows->getThumname();
			}
			$productName = $rows->getProduct();

			$playxml .= '<mainvideo  video_url="' . $video . '" video_hdpath= "" video_isLive ="false" allow_download="'.$pdownload.'" preroll_id="" postroll_id="" allow_preroll="false" allow_postroll="false" allow_midroll = "false" streamer_path="" preview_image="' . $timage . '"  thumb_image="' . $timage . '" video_id="' . $rows->id . '" >';
			$playxml .= '<title>';
			$playxml .= '<![CDATA[' . $productName . ']]>';
			$playxml .= '</title>';
			$playxml .= '<tagline targeturl="' . $targeturl . '">';
			$playxml .= '</tagline>';
			$playxml .= '</mainvideo>';
		}
		echo $playxml1;
		echo $playxml;
		echo '</playlist>';
		exit();
	}

	/* Create Configuration Xml */

	public function configAction() {

		/* Retrive Config Details from config table */
		$id = $this->getRequest()->getParam('id');
		$pid = $this->getRequest()->getParam('pid');
		$licenseKey = Mage::getStoreConfig('license/license/license');
		$playlistOpen = Mage::getStoreConfig('videoupload/videoupload/playlist_open');
		$autoplay = Mage::getStoreConfig('videoupload/videoupload/autoplay');
		$normalScale = Mage::getStoreConfig('videoupload/videoupload/normal_scale');
		$fullScreencale = Mage::getStoreConfig('videoupload/videoupload/full_scale');
		$logoPath = Mage::getStoreConfig('videoupload/logo/logo_src');
		$logoTarget = Mage::getStoreConfig('videoupload/logo/logo_target');
		$logoAlign = Mage::getStoreConfig('videoupload/logo/logo_align');
		$logoAlpha = Mage::getStoreConfig('videoupload/logo/logo_alpha');
		$volume = Mage::getStoreConfig('videoupload/videoupload/volume');
		$download = Mage::getStoreConfig('videoupload/videoupload/download');
		$skinAutohide = Mage::getStoreConfig('videoupload/videoupload/skin_autohide');
		$youtubeLogo = Mage::getStoreConfig('videoupload/videoupload/youtube');
		$stagecolor = '';
		/* get skin path fom js folder */
		$check = Mage::getStoreConfig('videoupload/videoupload/skin');
		if (!empty($check)) {
			$skin = $this->getSkinUrl('hdflvplayer/skin/' . Mage::getStoreConfig('videoupload/videoupload/skin') . '.swf');
		} else {
			$skin = $this->getSkinUrl('hdflvplayer/skin/skin_black.swf');
		}
		$adXML = $this->getSkinUrl('hdflvplayer/xml/ads.xml');
		$midrollXML = $this->getSkinUrl('hdflvplayer/xml/midroll.xml');
		$playlist = Mage::getStoreConfig('videoupload/videoupload/show_playlist');
		$empeded = Mage::getStoreConfig('videoupload/videoupload/empeded');
		$timer = Mage::getStoreConfig('videoupload/videoupload/timer');
		$zoom = Mage::getStoreConfig('videoupload/videoupload/zoom');
		$fullScreen = Mage::getStoreConfig('videoupload/videoupload/full_screen');
		$email = Mage::getStoreConfig('videoupload/videoupload/email');
		//$email = 'false';
		$baseUrl = Mage::getStoreConfig('web/secure/base_url');
		$language = Mage::getBaseUrl() . 'videoupload/index/language';
		$playlistXML = Mage::getBaseUrl() . 'videoupload/index/show?pid=' . $pid;
		/* Create config xml file for hdflv player */
		ob_clean();
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<config>';
		
		echo ' <stagecolor>0x000000</stagecolor> ';

		echo " <autoplay>$autoplay</autoplay>";

		echo '<buffer>3</buffer>';

		echo '  <volume>'.$volume.'</volume>';

		echo ' <normalscale>'.$normalScale.'</normalscale>';

		echo '  <fullscreenscale>'.$fullScreencale.'</fullscreenscale>';

		echo '   <license>'.$licenseKey.'</license>';

		echo '  <logopath>'.$logoPath.'</logopath>';

		echo '   <logoalpha>'.$logoAlpha.'</logoalpha>';

		echo '   <logoalign>'.$logoAlign.'</logoalign>';

		echo '    <logo_target>'.$logoTarget.'</logo_target>';



		echo '   <sharepanel_up_BgColor></sharepanel_up_BgColor>';

		echo '   <sharepanel_down_BgColor></sharepanel_down_BgColor>';

		echo '  <sharepaneltextColor>0x1d1d1d</sharepaneltextColor>';

		echo '   <sendButtonColor>0x1d1d1d</sendButtonColor>';

		echo ' <sendButtonTextColor>0xFFFFFF</sendButtonTextColor>';


		echo '   <textColor></textColor>';

		echo '    <skinBgColor></skinBgColor>';

		echo '  <seek_barColor></seek_barColor>';

		echo '  <buffer_barColor></buffer_barColor>';

		echo '  <skinIconColor></skinIconColor>';

		echo '    <pro_BgColor></pro_BgColor>';


		echo '   <playButtonColor></playButtonColor>';

		echo '   <playButtonBgColor></playButtonBgColor>';

		echo '   <playerButtonColor>0xFFFFFF</playerButtonColor>';

		echo '  <playerButtonBgColor></playerButtonBgColor>';


		echo '   <relatedVideoBgColor></relatedVideoBgColor>';

		echo '   <scroll_barColor></scroll_barColor>';

		echo '  <scroll_BgColor></scroll_BgColor>';


		echo '   <skin>skin/skin_hdflv_white.swf</skin>';

		echo '  <skin_autohide>'.$skinAutohide.'</skin_autohide>';

		echo '  <languageXML>'.$language.'</languageXML>';

		echo '  <playlistXML>'.$playlistXML.'</playlistXML>';

		echo ' <playlist_open>false</playlist_open>';

		echo ' <showPlaylist>false</showPlaylist>';

		echo ' <HD_default>true</HD_default>';

		echo ' <adXML>'.$adXML.'</adXML>';

		echo '   <preroll_ads>false</preroll_ads>';

		echo '   <postroll_ads>false</postroll_ads>';

		echo '  <midrollXML>'.$midrollXML.'</midrollXML>';

		echo '   <midroll_ads>false</midroll_ads>';

		echo ' <shareURL>'. Mage::getBaseUrl() . 'skin/frontend/default/default/hdflvplayer/email.php</shareURL>';

		echo '   <embed_visible>'.$empeded.'</embed_visible>';

		echo '  <Download>'.$download.'</Download>';

		echo '  <downloadUrl>'. Mage::getBaseUrl() . 'skin/frontend/default/default/hdflvplayer/download.php</downloadUrl>';

		echo '  <adsSkip>false</adsSkip>';

		echo ' <adsSkipDuration>5</adsSkipDuration>';

		echo ' <relatedVideoView>side</relatedVideoView>';

		echo '  <imaAds>false</imaAds>';

		echo ' <imaAdsXML>xml/ima.xml</imaAdsXML>';

		echo '  <trackCode></trackCode>';

		echo ' <showTag>true</showTag>';

		echo ' <timer>' . $timer . '</timer>';

		echo ' <zoomIcon>' . $zoom . '</zoomIcon>';

		echo '<email>' . $email . '</email>';

		echo '  <shareIcon>' . $email . '</shareIcon>';

		echo '  <fullscreen>' . $fullScreen . '</fullscreen>';

		echo '  <volumecontrol>true</volumecontrol>';

		echo '  <playlist_auto>false</playlist_auto>';

		echo ' <imageDefault>true</imageDefault>';
		
		echo '</config>';
		exit;
	}

	/* Create Language Xml */

	function languageAction() {
		ob_clean();
		header("content-type: text/xml");
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<language>';
		echo '<Play><![CDATA[Play]]></Play>';
		echo '<Pause><![CDATA[Pause]]></Pause>';
		echo '<Replay><![CDATA[Replay]]></Replay>';
		echo '<Changequality><![CDATA[Change Quality]]></Changequality>';
		echo '<zoomIn><![CDATA[Zoom In]]></zoomIn>';
		echo '<zoomOut><![CDATA[Zoom Out]]></zoomOut>';
		echo '<zoom><![CDATA[Zoom]]></zoom>';
		echo '<share><![CDATA[Share]]></share>';
		echo '<FullScreen><![CDATA[Full Screen]]></FullScreen>';
		echo '<ExitFullScreen><![CDATA[Exit Full Screen]]></ExitFullScreen>';
		echo '<PlayListHide><![CDATA[Hide Related Videos]]></PlayListHide>';
		echo '<PlayListView><![CDATA[Show Related Videos]]></PlayListView>';
		echo '<sharetheword><![CDATA[Share This Video]]></sharetheword>';
		echo '<sendanemail><![CDATA[Send an Email]]></sendanemail>';
		echo '<Mail><![CDATA[Email]]></Mail>';
		echo '<to><![CDATA[To Email]]></to>';
		echo '<from><![CDATA[From Email]]></from>';
		echo '<note><![CDATA[Message]]></note>';
		echo '<send><![CDATA[Send]]></send>';
		echo '<copy><![CDATA[Copy]]></copy>';
		echo '<link><![CDATA[Link]]></link>';
		echo '<social><![CDATA[Social]]></social>';
		echo '<embed><![CDATA[Embed]]></embed>';
		echo '<Quality><![CDATA[Quality]]></Quality>';
		echo '<facebook><![CDATA[Share on Facebook]]></facebook>';
		echo '<tweet><![CDATA[Share on Twitter]]></tweet>';
		echo '<tumblr><![CDATA[Share on Tumblr]]></tumblr>';
		echo '<google+><![CDATA[Share on Google+]]></google+>';
		echo '<autoplayOff><![CDATA[Turn Off Autoplay]]></autoplayOff>';
		echo '<autoplayOn><![CDATA[Turn On Autoplay]]></autoplayOn>';
		echo '<adindicator><![CDATA[Your selection will follow this sponsors message in - seconds]]></adindicator>';
		echo '<skip><![CDATA[Skip this ad now >>]]></skip>';
		echo '<skipvideo><![CDATA[You can skip to video in]]></skipvideo>';
		echo '<download><![CDATA[Download]]></download>';
		echo '<Volume><![CDATA[Volume]]></Volume>';
		echo '<ads><![CDATA[mid]]></ads>';
		echo '<nothumbnail><![CDATA[No Thumbnail Available]]></nothumbnail>';
		echo '<live><![CDATA[LIVE]]></live>';
		echo '<fill_required_fields><![CDATA[Please fill in all required fields.]]></fill_required_fields>';
		echo '<wrong_email><![CDATA[Missing field Or Invalid email]]></wrong_email>';
		echo '<email_wait><![CDATA[Wait..]]></email_wait>';
		echo '<email_sent><![CDATA[Thank you! Video has been sent.]]></email_sent>';
		echo '<video_not_allow_embed_player><![CDATA[The requested video does not allow playback in the embedded players.]]></video_not_allow_embed_player>';
		echo '<youtube_ID_Invalid><![CDATA[The video ID that does not have 11 characters, or if the video ID contains invalid characters.]]></youtube_ID_Invalid>';
		echo '<video_Removed_or_private><![CDATA[The requested video is not found. This occurs when a video has been removed (for any reason), or it has been marked as private.]]></video_Removed_or_private>';
		echo '<streaming_connection_failed><![CDATA[Requested streaming provider connection failed]]></streaming_connection_failed>';
		echo '<audio_not_found><![CDATA[The requested audio is not found or access denied]]></audio_not_found>';
		echo '<video_access_denied><![CDATA[The requested video is not found or access denied]]></video_access_denied>';
		echo '<FileStructureInvalid><![CDATA[Flash Player detects an invalid file structure and will not try to play this type of file. Supported by Flash Player 9 Update 3 and later.]]></FileStructureInvalid>';
	    echo '<NoSupportedTrackFound><![CDATA[Flash Player does not detect any supported tracks (video, audio or data) and will not try to play the file. Supported by Flash Player 9 Update 3 and later.]]></NoSupportedTrackFound>';
		
		echo '</language>';
		exit();
	}

	public function categoryAction() {

		$id = $this->getRequest()->getParam('id');
		if ($id == '') {
			echo "<select id='category_id' name='category_id' class='select' onchange='validate(this.value)'>";
			echo "<option value=''>Select</option>";
			echo "</select>";
		} else {
			echo "<select id='category_id' name='category_id' class='select' onchange='validate(this.value)'>";
			echo "<option value=''>Select</option>";
			$cat = Mage::getModel('catalog/category')->getCollection();
			$cat->addFieldToFilter('parent_id', Array('eq' => $id));
			foreach ($cat as $_cat) {
				$model = Mage::getModel('catalog/category');
				$_cat = $model->load($_cat->getId());
				echo "<option value=" . $_cat->getId() . ">" . $_cat->getName() . "</option>";
			}
			echo "</select>";
		}
	}

	public function productAction() {

		$id = $this->getRequest()->getParam('id');

		if ($id == '') {
			echo "<select id='entity_id' name='entity_id' class='required-entry required-entry select'>";
			$_productCollection = Mage::getResourceModel('catalog/product_collection');
			echo "<option value=''>Select</option>";
			foreach ($_productCollection as $_product) {
				$model = Mage::getModel('catalog/product');
				$_product = $model->load($_product->getId());
				$selected = '';
				echo "<option value=" . $_product->getId() . ">" . $_product->getName() . "</option>";
			}

			echo "</select>";
		} else {
			echo "<select id='entity_id' name='entity_id' class='required-entry required-entry select'>";
            $_productCollection = Mage::getResourceModel('catalog/product_collection')->addCategoryFilter(Mage::getModel('catalog/category')->load($id));
            echo "<option value=''>Select</option>";
            foreach ($_productCollection as $_product) {
                $model = Mage::getModel('catalog/product');
                $_product = $model->load($_product->getId());
                $selected = '';
                echo "<option value=" . $_product->getId() . ">" . $_product->getName() . "</option>";
            }

            echo "</select>";
        }
    }

}

