<?php 

class JR_AttributeOptionImage_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute 
{
    /**
     * Get data array for building attribute filter items
     *
     * Take care of it with every new Magento version, it's a copy cat.
     * 
     * @return array
     * 
     * @version 1.7.0.0
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        $this->_requestVar = $attribute->getAttributeCode();
    
        $key = $this->getLayer()->getStateKey().'_'.$this->_requestVar;
        $data = $this->getLayer()->getAggregator()->getCacheData($key);
    
        if ($data === null) {
            $options = $attribute->getFrontend()->getSelectOptions();
            $optionsCount = $this->_getResource()->getCount($this);
            $data = array();
            foreach ($options as $option) {
                if (is_array($option['value'])) {
                    continue;
                }
                if (Mage::helper('core/string')->strlen($option['value'])) {
                    // Check filter type
                    if ($this->_getIsFilterableAttribute($attribute) == self::OPTIONS_ONLY_WITH_RESULTS) {
                        if (!empty($optionsCount[$option['value']])) {
                            $data[] = array(
                                'label'     => $option['label'],
                                'value'     => $option['value'],
                                'image'     => $option['image'], // [patch]
                                'thumbnail' => $option['thumbnail'], // [patch]
                                'count'     => $optionsCount[$option['value']],
                            );
                        }
                    }
                    else {
                        $data[] = array(
                            'label'     => $option['label'],
                            'value'     => $option['value'],
                            'image'     => $option['image'], // [patch]
                            'thumbnail' => $option['thumbnail'], // [patch]
                            'count'     => isset($optionsCount[$option['value']]) ? $optionsCount[$option['value']] : 0,
                        );
                    }
                }
            }
    
            $tags = array(
                Mage_Eav_Model_Entity_Attribute::CACHE_TAG.':'.$attribute->getId()
            );
    
            $tags = $this->getLayer()->getStateTags($tags);
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
    
    /**
     * Initialize filter items
     *
     * @return  Mage_Catalog_Model_Layer_Filter_Abstract
     */
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items=array();
        foreach ($data as $itemData) {
            $items[] = $this->_createExtendedItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
                $itemData['image'],
                $itemData['thumbnail']
            );
        }
        $this->_items = $items;
        return $this;
    }
    
    /**
     * Create extended filter item object
     *
     * @param   string $label
     * @param   mixed $value
     * @param   int $count
     * @param   string $image
     * @param   string $thumbnail
     * @return  Mage_Catalog_Model_Layer_Filter_Item
     */
    protected function _createExtendedItem($label, $value, $count = 0, $image = '', $thumbnail = '')
    {
        return Mage::getModel('catalog/layer_filter_item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count)
            ->setImage($image)
            ->setThumbnail($thumbnail);
    }
}

