<?php 

/**
 * Catalog attribute option resource model
 *
 */
class JR_AttributeOptionImage_Model_Resource_Catalog_Attribute_Option extends Mage_Eav_Model_Resource_Entity_Attribute_Option 
{
    
    /**
     * Resource initialization
     */
    protected function _construct() 
    {
        $this->_init('aoi/catalog_eav_attribute_option', 'option_id');
    }
}