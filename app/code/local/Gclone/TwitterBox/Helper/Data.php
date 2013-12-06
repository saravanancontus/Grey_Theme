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

class Gclone_TwitterBox_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function configValue($path, $name)
	{
		$basePath  = 'twitterbox/';
		$basePath .= $path;

		if( substr($basePath, 0, -1) != '/' )
		{
			$basePath .= '/';
		}

		return Mage::getStoreConfig( $basePath . $name );
	}

	/* general */

	public function getActive()
	{
		$value = $this->configValue('general', 'active');
		return ( $value ) ? true : false ;
	}


	public function getUserName()
	{
		$value = $this->configValue('general', 'username');
		return $value;
	}
	public function getTitle()
	{
		$value = $this->configValue('general', 'title');
		return $value;
	}
        public function getCaption()
	{
		$value = $this->configValue('general', 'caption');
		return $value;
	}
        public function getSearch()
	{
		$value = $this->configValue('general', 'search');
		return $value;
	}
	/* tweets */

	public function getCount()
	{
		$value = $this->configValue('tweets', 'count');

		if( !is_numeric($value) || empty($value) )
		{
			return '4';
		}

		return $value;
	}

	public function getLive()
	{
		$value = $this->configValue('tweets', 'poll');
		return ( $value ) ? "true" : "false" ;
	}

	public function getBehavior()
	{
		$value = $this->configValue('tweets', 'behavior');

		if( empty($value) )
		{
			return 'all';
		}

		return $value;
	}

	public function getInterval()
	{
		$value = $this->configValue('tweets', 'interval');

		if( !is_numeric($value) || empty($value) )
		{
			return '2000';
		}

		return ($value * 1000);
	}

	public function getLoop()
	{
		$value = $this->configValue('tweets', 'loop');
		return ( $value ) ? "true" : "false" ;
	}

	public function getHashtags()
	{
		$value = $this->configValue('tweets', 'hashtag');
		return ( $value ) ? "true" : "false" ;
	}

	public function getTimestamps()
	{
		$value = $this->configValue('tweets', 'timestamp');
		return ( $value ) ? "true" : "false" ;
	}

	public function getAvatars()
	{
		$value = $this->configValue('tweets', 'avatar');
		return ( $value ) ? "true" : "false" ;
	}

	/* size */

	public function getAutoWidth()
	{
		$value = $this->configValue('size', 'auto');
		return ( $value ) ? true : false ;
	}

	public function getWidth()
	{
		if( $this->getAutoWidth() )
		{
			return "'auto'";
		}

		$value = $this->configValue('size', 'width');

		if( !is_numeric($value) || empty($value) )
		{
			return '250';
		}

		return $value;
	}

	public function getHeight()
	{
		$value = $this->configValue('size', 'height');

		if( !is_numeric($value) || empty($value) )
		{
			return '350';
		}

		return $value;
	}

	public function getScrollbar()
	{
		$value = $this->configValue('size', 'scrollbar');
		return ( $value ) ? "true" : "false" ;
	}

	/* colors */

	public function getShellColor()
	{
		$value = $this->configValue('color', 'shell');

		if( empty($value) || strlen($value) != 7 || $value{0} != '#' )
		{
			return "#ffffff";
		}

		return $value;
	}

	public function getTweetColor()
	{
		$value = $this->configValue('color', 'tweet');

		if( empty($value) || strlen($value) != 7 || $value{0} != '#' )
		{
			return "#ffffff";
		}

		return $value;
	}

	public function getTweetLinkColor()
	{
		$value = $this->configValue('color', 'link');

		if( empty($value) || strlen($value) != 7 || $value{0} != '#' )
		{
			return "#4aed05";
		}

		return $value;
	}

	public function getShellBackgroundColor()
	{
		$value = $this->configValue('color', 'shellbackground');

		if( empty($value) || strlen($value) != 7 || $value{0} != '#' )
		{
			return "#333333";
		}

		return $value;
	}

	public function getTweetBackgroundColor()
	{
		$value = $this->configValue('color', 'tweetbackground');

		if( empty($value) || strlen($value) != 7 || $value{0} != '#' )
		{
			return "#000000";
		}

		return $value;
	}
}