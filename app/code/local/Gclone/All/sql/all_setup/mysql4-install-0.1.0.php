<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 * The CONTUS GCLONE License is available at this URL:
 * http://www.groupclone.net/GCLONE-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */

$installer = $this;

$installer->startSetup();

$connection = $installer->getConnection();
/* @var $connection Varien_Db_Adapter_Pdo_Mysql */


$installer->startSetup();

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Business',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'business',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>For Businesses</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;Business</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'How it Works',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'works',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>How it Works</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;works</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Terms',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'terms',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>Terms and Conditions</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;Terms</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Preview Page',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'previewpage',
    'content'           => "<div>\r\n<p>{{block type=\"catalog/product_view\" category_id=\"3\" template=\"catalog/product/view.phtml\"}}</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'about us',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'about',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>About Us</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;about us</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'FAQ',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'faq',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>Frequently Asked Questions</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;FAQ</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Privacy Policy',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'privacy',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>Privacy Policy</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;Privacy Policy</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/page'), array(
    'title'             => 'Press',
    'root_template'     => 'two_columns_right',
    'identifier'        => 'press',
    'content'           => "<div id=\"business\">\r\n<div class=\"page-title\">\r\n<h1>Press</h1>\r\n</div>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\">Content</span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong><span style=\"padding: 0px; margin: 0px; text-decoration: underline;\"><span style=\"font-size: medium; padding: 0px; margin: 0px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></strong></p>\r\n<p style=\"padding: 0px; margin: 0px;\">To enter the content go to login to \"<strong>admin -&gt;CMS-&gt;pages-&gt;Press</strong>\" page</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n<p style=\"padding: 0px; margin: 0px;\"><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<p style=\"padding: 0px; margin: 0px;\">&nbsp;</p>\r\n</div>",
    'creation_time'     => now(),
    'update_time'       => now(),
));
$connection->insert($installer->getTable('cms/page_store'), array(
    'page_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

//updates

$connection->update($installer->getTable('cms/page'), array(
'layout_update_xml'=>"<reference name=\"head\">\r\n<action method=\"addCss\"><stylesheet>css/homecontent.css</stylesheet></action>\r\n<action method=\"addCss\"><stylesheet>css/header.css</stylesheet></action>\r\n<action method=\"addJs\"><script>prototype/prototype.js</script></action>\r\n  <action method=\"addJs\"><script>scriptaculous/effects.js</script></action>\r\n <action method=\"addCss\"><stylesheet>css/reset.css</stylesheet></action>\r\n<action method=\"addItem\"><type>skin_js</type><name>js/sleight.js</name></action>\r\n</reference>"), 
"identifier='previewpage'"
);

$connection->update($this->getTable('cms/block'), array(
'content'=>"<div class=\"clsfoot-div\">\r\n
<h4>Company</h4>\r\n
<ul class=\"clsfooterul\">\r\n
    <li><a href=\"{{store url =''}}\">Home</a></li>\r\n
    <li><a href=\"{{store url ='about'}}\">About Us</a></li>\r\n
    <li><a href=\"{{store url =''}}\">Blog</a></li>\r\n
    <li><a href=\"{{store url ='press'}}\">Press</a></li>\r\n
</ul>\r\n
</div>\r\n
<div class=\"clsfoot-div\">\r\n
    <h4>Get Help</h4>\r\n
    <ul class=\"clsfooterul\">\r\n
        <li><a href=\"{{store url ='faq'}}\">FAQ</a></li>\r\n
        <li><a href=\"{{store url ='terms'}}\">Terms of Use</a></li>\r\n
        <li><a href=\"{{store url ='privacy'}}\">Privacy Statement</a></li>\r\n
        <li><a href=\"{{store url ='works'}}\">How It Works</a></li>\r\n        
    </ul>\r\n
</div>\r\n
<div class=\"clsfoot-div\" style=\"width:500px;\">\r\n
    <h4>Unbeatable Deals for Local Adventures</h4>\r\n
    <p>Contus Groupclone is an easy way to get huge discounts while discovering fun activities in your city. Our daily local deals consist of restaurants, spas, things to do, nightlife, massages, salons, hotels, and a whole lot more, in hundreds of cities across the world.</p>\r\n
</div>\r\n"),
"identifier='footer_links'"
);

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Copyrights',
    'identifier'        => 'copyrights',
    'content'           => "<div class=\"clscopyright\">&copy;2013 Contus Groupclone{{config path=\"general/store_information/name\"}}. All rights reserved.</div>\r\n",
    'creation_time'     => now(),
    'update_time'       => now(),
));


//cms_block

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'How to use coupon',
    'identifier'        => 'coupon-use',
    'content'           => "<p><span style=\"color: #181818; font-family: Arial, Helvetica, sans-serif; font-size: 20px;\"><strong>How to use this: </strong> </span></p>\r\n<p><span style=\"color: #181818; font-family: Arial, Helvetica, sans-serif; font-size: 12px;\">1. Print coupon. We recommend that you re-use the back-side of this paper for your next coupon, and help reduce waste.  <br /> 2. Check the<strong> Fine Print.</strong> Make an appointment or reservation if required.  <br /> 3. Present coupon to merchant.  <br /> 4. Enjoy!  <br /><br />*Remember: Customers <strong>tip on the full amount</strong> of the pre-discounted service (and tip generously).  We support sustainable businesses because they share our values.</span></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Coupon footer',
    'identifier'        => 'coupon-footer',
    'content'           => "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#dbdbdb\">\r\n<tbody>\r\n<tr>\r\n<td width=\"5%\">&nbsp;</td>\r\n<td style=\"padding: 5px 0;\" width=\"48%\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: xx-small;\"> Support: 1-800-650-1030 Monday-Friday 10am-6pm IST</span></td>\r\n<td width=\"2%\">&nbsp;</td>\r\n<td style=\"padding: 5px 0;\" width=\"40%\" valign=\"top\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: xx-small;\">Email : {{config path=\"trans_email/ident_general/email\"}}</span></td>\r\n<td width=\"5%\">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Privacy Policy',
    'identifier'        => 'privacypolicy',
    'content'           => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vulputate pharetra tincidunt. Praesent vitae mauris lacus, sed sollicitudin lorem. Sed tellus ligula, mollis non iaculis nec, feugiat consequat est. Cras elit leo, malesuada malesuada ultricies et, lacinia ac orci. In hac habitasse platea dictumst. Aliquam pellentesque erat non dui varius ultrices. Vivamus tincidunt consequat ipsum, at imperdiet neque cursus a. Nam vel tellus et velit malesuada pulvinar nec a lectus. Vivamus enim nibh, varius in facilisis in, ultrices eu nibh. Pellentesque tristique metus nec sem pharetra posuere venenatis diam aliquet. Duis in augue turpis, nec volutpat elit. Nullam semper egestas orci, et congue nisl ultricies eget. Mauris nec nisi a turpis egestas venenatis. Cras sed est ac ipsum vulputate mollis.",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Footer iphone App',
    'identifier'        => 'footer-iphone',
    'content'           => "<p><a href=\"http://itunes.apple.com/app/groupclone/id413499577?mt=8\" target=\"_blank\"><img src=\"{{media url=\"footer/icon_iphone.png\"}}\" alt=\"Iphone App\" /></a><a></a></p>\r\n<p><a> </a></p>\r\n<h4><a></a><a href=\"http://itunes.apple.com/app/groupclone/id413499577?mt=8\" target=\"_blank\">Download iPhone App</a></h4>\r\n<p><a href=\"http://itunes.apple.com/app/groupclone/id413499577?mt=8\" target=\"_blank\">Get iPhone App. Just one click away</a></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
	'is_active'			=>	0
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Footer Merchant',
    'identifier'        => 'footer-merchant',
    'content'           => "<p><a href=\"{{store url ='merchant'}}\"><img src=\"{{media url=\"footer/icon_mlogin.png\"}}\" alt=\"Merchant Login\" /></a></p>\r\n<h4><a href=\"{{store url ='merchant'}}\">Merchant Login</a></h4>\r\n<p><a href=\"{{store url ='merchant'}}\">Merchant Login - Start your own deal and enjoy the benefit</a></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Footer refer a friend',
    'identifier'        => 'footer-refer',
    'content'           => "<p><a href=\"{{store url ='advertsystem/index/invitation'}}\"><img src=\"{{media url=\"footer/icon_friend.png\"}}\" alt=\"\" /></a></p>\r\n<h4><a href=\"{{store url ='advertsystem/index/invitation'}}\">Invite your Friends ! </a></h4>\r\n<p><a href=\"{{store url ='advertsystem/index/invitation'}}\">Refer a friend and get discount, via Advert System</a></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Footer Paypal',
    'identifier'        => 'footer-paypal',
    'content'           => "<p><img src=\"{{media url='footer/icon_paypal.png'}}\" alt=\"PayPal\" /></p>\r\n<h4>Secure PayPal</h4>\r\n<p>Make a secure payment via PayPal.</p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Side Banner',
    'identifier'        => 'side_banner',
    'content'           => "<p><img src=\"{{skin url=\"images/banner/side_banner.png\"}}\" alt=\"Side Banner\" /></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));

$connection->insert($installer->getTable('cms/block'), array(
    'title'             => 'Footer Featured Business',
    'identifier'        => 'footer-fbusiness',
    'content'           => "<p><a href=\"{{store url ='fbusiness'}}\"><img title=\"fbusiness\" src=\"{{media url=\"footer/fbusiness-1.png\"}}\" alt=\"fbusiness\" /></a></p>\r\n<h4><a href=\"{{store url ='fbusiness'}}\">For Your Business</a></h4>\r\n<p><a href=\"{{store url ='fbusiness'}}\">Get your business featured on {{config path=\"general/store_information/name\"}} and enjoy the benefits.</a></p>",
    'creation_time'     => now(),
    'update_time'       => now(),
));

$connection->insert($installer->getTable('cms/block_store'), array(
    'block_id'   => $connection->lastInsertId(),
    'store_id'  => 1
));




//$installer->getConnection()->update($attributeTable, array('value'=>$value), "value_id=" . $row['value_id']);

$installer->run("
ALTER TABLE {$installer->getTable('gift_message')} ADD `email` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE {$installer->getTable('gift_message')} ADD `product_id` INT NOT NULL;
ALTER TABLE {$installer->getTable('gift_message')} ADD `order_id` INT NOT NULL;
ALTER TABLE {$installer->getTable('gift_message')} ADD `type` INT NOT NULL;
");



$connection->insert($installer->getTable('admin_role'), array(
    'parent_id'     => 0,
    'tree_level'    => 1,
    'sort_order'    => 0,
    'role_type'    	=> 'G',
	'user_id'		=>	0,
	'role_name'		=> 'merchant'
));

$lastInsertRole = $connection->lastInsertId();


$installer->run("
INSERT INTO {$installer->getTable('admin_rule')} (`role_id`, `resource_id`, `privileges`,
`assert_id`, `role_type`, `permission`) VALUES


($lastInsertRole, 'all', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/dashboard', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/acl', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/acl/roles', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/acl/users', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/store', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/design', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/general', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/web', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/design', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/system', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/advanced', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/system/config/trans_email', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/system/config/dev', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/currency', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/sendfriend', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/admin', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/cms', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/catalog', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/payment', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/payment_services', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/sales', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/sales_email', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/sales_pdf', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/cataloginventory', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/shipping', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/carriers', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/promo', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/checkout', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/paypal', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/google', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/reports', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/tax', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/wishlist', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/contacts', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/system/config/sitemap', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/rss', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/api', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/oauth', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/newsletter', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/downloadable', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/persistent', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/onestepcheckout', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/sociallogin', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/background', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/license', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/merchant', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/videoupload', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/mailchimp', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/advertsystem', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/all', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/dealcoupon', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/emailnewsletter', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/fbusiness', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/rssfeed', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/barcode', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/constantcontact', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/deal', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/twitterbox', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/config/moneybookers', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/currency', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/currency/rates', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/currency/symbols', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/system/email_template', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/variable', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/myaccount', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/tools', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/tools/backup', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/tools/backup/rollback', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/tools/compiler', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/convert', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/convert/gui', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/convert/profiles', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/convert/import', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/convert/export', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/cache', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/extensions', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/extensions/local', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/extensions/custom', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/adminnotification', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/adminnotification/show_toolbar', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/adminnotification/show_list', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/adminnotification/mark_as_read', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/adminnotification/remove', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/index', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/order_statuses', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/users', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/roles', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/consumer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/consumer/edit', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/consumer/delete', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/authorizedTokens', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/oauth_admin_token', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_roles', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_roles/add', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_roles/edit', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_roles/delete', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/api/rest_attributes/edit', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/system/license', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/global_search', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/block', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/page', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/page/save', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/page/delete', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/media_gallery', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/poll', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/cms/widget_instance', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/customer/group', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/customer/manage', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/customer/online', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/catalog/attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/attributes/attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/attributes/sets', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/categories', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/products', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/catalog/update_attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/urlrewrite', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/search', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/reviews_ratings', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/reviews_ratings/reviews', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/reviews_ratings/reviews/all', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/reviews_ratings/reviews/pending', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/reviews_ratings/ratings', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/tag', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/tag/all', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/tag/pending', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/catalog/sitemap', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/promo', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/promo/catalog', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/promo/quote', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/create', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/view', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/email', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/order/actions/reorder', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/edit', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/cancel', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/review_payment', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/capture', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/invoice', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/creditmemo', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/hold', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/unhold', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/ship', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/comment', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/order/actions/emails', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/sales/invoice', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/shipment', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/creditmemo', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/transactions', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/transactions/fetch', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/recurring_profile', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/billing_agreement', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/billing_agreement/actions', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/billing_agreement/actions/view', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/billing_agreement/actions/manage', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/billing_agreement/actions/use', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/checkoutagreement', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax/classes_customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax/classes_product', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax/import_export', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax/rates', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/tax/rules', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/sales/couponvalidator', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/report', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/paypal_settlement_reports', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/paypal_settlement_reports/view', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/paypal_settlement_reports/fetch', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/sales', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/tax', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/shipping', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/invoiced', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/refunded', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/salesroot/coupons', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/shopcart', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/shopcart/product', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/shopcart/abandoned', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products/bestsellers', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products/sold', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products/viewed', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products/lowstock', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/products/downloads', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/customers', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/customers/accounts', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/customers/totals', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/customers/orders', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/review', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/review/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/review/product', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/tags', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/tags/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/tags/popular', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/tags/product', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/search', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/report/statistics', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/newsletter', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/newsletter/problem', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/newsletter/queue', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/newsletter/subscriber', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/newsletter/template', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/page_cache', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/xmlconnect', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/xmlconnect/mobile', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/xmlconnect/history', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/xmlconnect/templates', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/xmlconnect/queue', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals/ManageDeals', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals/ManageDeals/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/Adddeals', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals/Adddeals/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/items', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/items/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/couponvalidator', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals/couponvalidator/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/categories', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/categories/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/attributes/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/order', NULL, 0, 'G', 'allow'),
($lastInsertRole, 'admin/all/Deals/order/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/deal', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/Deals/deal/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/rules', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/rules/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/referral', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/referral/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/config', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/advertsystem/config/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/license', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/license/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/dealcoupon', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/dealcoupon/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/currency', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/currency/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/emailnewsletter', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/fbusiness', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/fbusiness/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/customer/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/trans_email', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/configuration/trans_email/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/share', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/share/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/sociallogin', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/sociallogin/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/twitterbox', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/socialshare/customer', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/payment', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/payment/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/onestepcheckout', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/checkout', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/checkout/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/carriers', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/carriers/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/catalog', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/catalog/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/catalog/depends', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/catalog/depends/module', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/shipping', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/shipping/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/merchant', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/merchant/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/tax', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/tax/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/sales_email', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/sales_email/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/sales', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/paymentconfiguration/sales/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/background', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/background/background', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/background/background/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/background/design', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/background/design/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/rss', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/rss/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/constantcontact', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/constantcontact/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/mailchimp', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/mailchimp/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/google', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/google/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/sitemap', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/optional/sitemap/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/general', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/general/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/attributes', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/attributes/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/payment', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/all/localization/payment/action', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Apptha_Adddeals', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Apptha_Onestepcheckout', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Apptha_Sociallogin', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Gclone_Advertsystem', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Gclone_Deal', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Gclone_Dealcoupon', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Gclone_Fbusiness', NULL, 0, 'G', 'deny'),
($lastInsertRole, 'admin/Gclone_Rssfeed', NULL, 0, 'G', 'deny');
");


$installer->endSetup();
