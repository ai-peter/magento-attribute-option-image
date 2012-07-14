<?php

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer       = $this;
$connection      = $installer->getConnection();

$mainTable       = $installer->getTable('eav_attribute_option');
$additionalTable = $installer->getTable('aoi/catalog_eav_attribute_option');

$installer->startSetup();

/**
 * Create table 'aoi/catalog_eav_attribute_option'
 */
if (!$connection->isTableExists($additionalTable)) {
    $table = $installer->getConnection()
        ->newTable($additionalTable)
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Option ID')
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Image')
        ->addColumn('thumbnail', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Thumbnail Image')
        ->addIndex($installer->getIdxName('aoi/catalog_eav_attribute_option', array('option_id')),
            array('option_id'))
        ->addForeignKey(
            $installer->getFkName(
                'eav/attribute_option', 'option_id', 
                'aoi/catalog_eav_attribute_option', 'option_id'
            ),
            'option_id', $installer->getTable('eav/attribute_option'), 'option_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment('Catalog Attribute Option');

    $installer->getConnection()->createTable($table);
}

/**
 * Fill out relation 'aoi/catalog_eav_attribute_option' with options ids, images and thumbnails
 */
if ($connection->tableColumnExists($mainTable, 'image')
    && $connection->tableColumnExists($mainTable, 'thumb')) {
    $select = $connection->select()
        ->from(
            array('main_table' => $mainTable), 
            array('main_table.option_id', 'main_table.image', 'main_table.thumb')
        )
        ->where('main_table.image IS NOT NULL OR main_table.thumb IS NOT NULL');
    $query = $select->insertFromSelect($additionalTable, array('image', 'thumbnail'));
    $connection->query($query);
}

/**
 * Eliminate obsolete columns
 */
$connection->dropColumn($mainTable, 'image');
$connection->dropColumn($mainTable, 'thumb');

$installer->endSetup();