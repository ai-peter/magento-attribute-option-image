<?php 

/**
 * Catalog attribute option model
 *
 */
class JR_AttributeOptionImage_Model_Resource_Catalog_Eav_Attribute_Option extends Mage_Eav_Model_Entity_Attribute_Option 
{
    
    /**
     * Resource initialization
     */
    public function _construct() 
    {
        $this->_init('aoi/catalog_attribute_option', 'option_id');
    }
}