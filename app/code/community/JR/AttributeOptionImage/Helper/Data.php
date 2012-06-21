<?php

class JR_AttributeOptionImage_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Prepare product attribute option image URL
     * 
     * @param string $url The image URL
     * @return string
     */
    public function getImageUrl($url) 
    {
        
        if ($url && (strpos($url, 'http') !== 0)) {
            $url = Mage::getDesign()->getSkinUrl($url);
        }

        return $url;
    }
}