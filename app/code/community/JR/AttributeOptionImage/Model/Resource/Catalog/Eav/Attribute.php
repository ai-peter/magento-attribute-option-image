<?php 

/**
 * Catalog attribute model
 */
class JR_AttributeOptionImage_Model_Resource_Catalog_Eav_Attribute extends Mage_Catalog_Model_Resource_Eav_Attribute 
{
    
    /**
     * Get default attribute source model
     *
     * @return string
     */
    public function _getDefaultSourceModel() 
    {
        return 'aoi/catalog_entity_attribute_source_table';
    }
}