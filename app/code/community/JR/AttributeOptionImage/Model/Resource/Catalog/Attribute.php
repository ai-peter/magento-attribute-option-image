<?php

class JR_AttributeOptionImage_Model_Resource_Catalog_Attribute extends Mage_Catalog_Model_Resource_Attribute
{
    /**
     * Perform actions before object save
     *
     * @see Mage_Eav_Model_Resource_Entity_Attribute::_beforeSave()
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Catalog_Model_Resource_Attribute
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) 
    {
        parent::_beforeSave($object);
        
        if ((!$object->getId() || $object->getSourceModel() == 'eav/entity_attribute_source_table')
            && ($object->getFrontendInput() == 'select' && $object->getBackendType() == 'int')) {
            $object->setSourceModel('aoi/catalog_entity_attribute_source_table');
        }
        
        return $this;
    }
    
    /**
     * Perform actions after object save
     *
     * @param  Mage_Core_Model_Abstract $object
     * @return Mage_Catalog_Model_Resource_Attribute
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object) 
    {
        parent::_afterSave($object);
        
        return $this->_saveOptionAdditionals($object);
    }
    
    /**
     * Save attribute option additionals
     * 
     * @param Mage_Core_Model_Abstract $object
     * @return Wee_Attribute_Model_Resource_Catalog_Attribute
     */
    protected function _saveOptionAdditionals(Mage_Core_Model_Abstract $object) 
    {
        
        $option = $object->getOption();
        
        if (is_array($option)) {
            
            $adapter = $this->_getWriteAdapter();
            $table   = $this->getTable('aoi/catalog_eav_attribute_option');
                
            if (isset($option['value'])) {
                
                foreach ($option['value'] as $id => &$values) {
                    
                    $intId = (int)$id;
                    
                    if (!$intId || !empty($option['delete'][$id])) {
                        continue;
                    }
                    
                    $image     = !empty($option['image'][$id]) ? $option['image'][$id] : null;
                    $thumbnail = !empty($option['thumbnail'][$id]) ? $option['thumbnail'][$id] : null;
                    
                    $data = array(
                        'option_id' => $intId,
                        'image' => $image,
                        'thumbnail' => $thumbnail
                    );
                    
                    $adapter->insertOnDuplicate($table, $data);
                }
            }
        }
        
        return $this;
    }
}