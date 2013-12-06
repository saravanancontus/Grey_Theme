<?php
$installer = $this;
$installer->startSetup();

//create the 'subtitle' attribute
$installer->addAttribute('catalog_product', 'subtitle', array(
    'group' => 'Deal Information',
    'label' => 'Subtitle',
    'type' => 'varchar',
    'input' => 'text',
    'default' => '',
    'class' => '',
    'backend' => '',
    'frontend' => '',
    'source' => '',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'unique' => false,
    'apply_to' => 'dealproduct',
));
$installer->updateAttribute('catalog_product', 'subtitle', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute('catalog_product', 'subtitle', 'apply_to', 'dealproduct');

//create the 'Featured Deal' attribute
$installer->addAttribute('catalog_product', 'featureddeal', array(
    'group' => 'Deal Information',
    'label' => 'Featured Deal',
    'type' => 'int',
    'input' => 'select',
    'default' => '',
    'class' => '',
    'backend' => '',
    'frontend' => '',
    'source' => 'eav/entity_attribute_source_boolean',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'unique' => false,
    'apply_to' => 'dealproduct',
));
$installer->updateAttribute('catalog_product', 'featureddeal', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute('catalog_product', 'featureddeal', 'apply_to', 'dealproduct');

//create the 'Travel Deal' attribute
$installer->addAttribute('catalog_product', 'travel', array(
    'group' => 'Deal Information',
    'label' => 'Travel Deal',
    'type' => 'int',
    'input' => 'select',
    'default' => '',
    'class' => '',
    'backend' => '',
    'frontend' => '',
    'source' => 'eav/entity_attribute_source_boolean',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'unique' => false,
    'apply_to' => 'dealproduct',
));
$installer->updateAttribute('catalog_product', 'travel', 'is_global', Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE);
$installer->updateAttribute('catalog_product', 'travel', 'apply_to', 'dealproduct');

//insert url rewrite for the goods and getaways
$result = $installer->run("
    SELECT `request_path` FROM {$installer->getTable('core_url_rewrite')} WHERE `id_path`='deal/index/goods'
");

if($result == '') {
$installer->run("
    INSERT INTO {$installer->getTable('core_url_rewrite')} (`store_id`, `category_id`, `product_id`, `id_path`, `request_path`, `target_path`, `is_system`, `options`, `description`) VALUES
(0, NULL, NULL, 'deal/index/goods', 'goods.html', 'deal/index/goods', 0, '', 'Goods')
");
}

$result1 = $installer->run("
    SELECT `request_path` FROM {$installer->getTable('core_url_rewrite')} WHERE `id_path`='deal/index/getaways'
");

if($result1 == '') {
$installer->run("
    INSERT INTO {$installer->getTable('core_url_rewrite')} (`store_id`, `category_id`, `product_id`, `id_path`, `request_path`, `target_path`, `is_system`, `options`, `description`) VALUES
(0, NULL, NULL, 'deal/index/getaways', 'getaways.html', 'deal/index/getaways', 0, '', 'Goods')
");
}

$installer->run("
    UPDATE {$installer->getTable('core_url_rewrite')} SET `request_path`='/',`description`='All Deals' WHERE `id_path`='deal/index/active'
");

$column = $installer->run("
    SHOW COLUMNS FROM {$installer->getTable('core_url_rewrite')} LIKE 'type'
");

if($column == '') {
$installer->run("
ALTER TABLE {$installer->getTable('gift_message')} ADD `type` INT NOT NULL;
");
}

$installer->endSetup();
?>