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

class Contus_Videoupload_Model_Videoupload extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('videoupload/videoupload');
    }
       /*     * ********************************************************** Return Youtube single video******************************************************** */

    public function hd_GetSingleYoutubeVideo($youtube_media) {

            if ($youtube_media == '')
            return;

        $url = 'http://gdata.youtube.com/feeds/api/videos/' . $youtube_media;

        $ytb  = $this->hd_ParseYoutubeDetails($this->hd_GetYoutubePage($url));


        return $ytb[0];
    }

    /*     * ********************************************************* Parse xml from Youtube****************************************************************** */

    public function hd_ParseYoutubeDetails($ytVideoXML) {

        // Create parser, fill it with xml then delete it

        $yt_xml_parser = xml_parser_create();

        xml_parse_into_struct($yt_xml_parser, $ytVideoXML, $yt_vals);

        xml_parser_free($yt_xml_parser);
        // Init individual entry array and list array

        $yt_video = array();

        $yt_vidlist = array();

        // is_entry tests if an entry is processing

        $is_entry = true;

// is_author tests if an author tag is processing

        $is_author = false;

        foreach ($yt_vals as $yt_elem) {

            // If no entry is being processed and tag is not start of entry, skip tag

            if (!$is_entry && $yt_elem['tag'] != 'ENTRY')
                continue;

            // Processed tag

            switch ($yt_elem['tag']) {
                case 'ENTRY' :
                    if ($yt_elem['type'] == 'open') {

                        $is_entry = true;

                        $yt_video = array();
                    } else {

                        $yt_vidlist[] = $yt_video;

                        $is_entry = false;
                    }
                    break;
                case 'ID' :

                    $yt_video['id'] = substr($yt_elem['value'], -11);

                    $yt_video['link'] = $yt_elem['value'];

                    break;

                case 'PUBLISHED' :

                    $yt_video['published'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);

                    break;
                case 'UPDATED' :

                    $yt_video['updated'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);

                    break;
                case 'MEDIA:TITLE' :

                    $yt_video['title'] = $yt_elem['value'];

                    break;
                case 'MEDIA:KEYWORDS' :

                    $yt_video['tags'] = $yt_elem['value'];

                    break;
                case 'MEDIA:DESCRIPTION' :

                    $yt_video['description'] = $yt_elem['value'];

                    break;
                case 'MEDIA:CATEGORY' :

                    $yt_video['category'] = $yt_elem['value'];

                    break;
                case 'YT:DURATION' :

                    $yt_video['duration'] = $yt_elem['attributes'];

                    break;
                case 'MEDIA:THUMBNAIL' :
                    if ($yt_elem['attributes']['HEIGHT'] == 240) {

                        $yt_video['thumbnail'] = $yt_elem['attributes'];

                        $yt_video['thumbnail_url'] = $yt_elem['attributes']['URL'];
                    }
                    break;
                case 'YT:STATISTICS' :

                    $yt_video['viewed'] = $yt_elem['attributes']['VIEWCOUNT'];

                    break;
                case 'GD:RATING' :

                    $yt_video['rating'] = $yt_elem['attributes'];

                    break;
                case 'AUTHOR' :

                    $is_author = ($yt_elem['type'] == 'open');

                    break;
                case 'NAME' :
                    if ($is_author)
                        $yt_video['author_name'] = $yt_elem['value'];

                    break;
                case 'URI' :
                    if ($is_author)
                        $yt_video['author_uri'] = $yt_elem['value'];

                    break;
                default :
            }
        }

        unset($yt_vals);

        return $yt_vidlist;
    }

    /*     * ************************************************************ Returns content of a remote page************************************************************ */
    /* Still need to do it without curl */

    public function hd_GetYoutubePage($url) {

        // Try to use curl first
        if (function_exists('curl_init')) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $xml = curl_exec($ch);

            curl_close($ch);
        }
        // If not found, try to use file_get_contents (requires php > 4.3.0 and allow_url_fopen)
        else {
            $xml = @file_get_contents($url);
        }

        return $xml;
    }
}