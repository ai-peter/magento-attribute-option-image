<?php

/**
 * Catalog attribute option collection
 * 
 */
class JR_AttributeOptionImage_Model_Resource_Catalog_Attribute_Option_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection 
{
    
    /**
     * @var string
     */
    protected $_additionalTable;
    
    /**
     * Resource initialization
     */
    protected function _construct() 
    {
        // use custom model for feature usage
        $this->_init('aoi/resource_catalog_eav_attribute_option', 'eav/entity_attribute_option');
        $this->_additionalTable = Mage::getSingleton('core/resource')->getTableName('aoi/catalog_eav_attribute_option');
        // use eav attribute option values
        $this->_optionValueTable = Mage::getSingleton('core/resource')->getTableName('eav/attribute_option_value');
    }
    
    /**
     * Init collection select
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    protected function _initSelect() 
    {
        // call parent
        parent::_initSelect();
        // join additonal data
        $this->getSelect()->joinLeft(
            array('additional_table' => $this->_additionalTable),
            'additional_table.option_id = main_table.option_id',
            array('image', 'thumbnail')
        );
        return $this;
    }
    
    /**
     * Convert collection items to select options array
     *
     * @param string $valueKey
     * @return array
     */
    public function toOptionArray($valueKey = 'value') 
    {
        return $this->_toOptionArray('option_id', $valueKey, array('image' => 'image', 'thumbnail' => 'thumbnail'));
    }
}