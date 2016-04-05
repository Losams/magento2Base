<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zero\Base\Model\Pagefield;

class ConverterTpl implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @param mixed $dom
     * @return array
     */
    public function convert($dom)
    {
        $extractedData = [];

        $attributeNamesList = [
            'name',
            'path',
        ];

        $xpath = new \DOMXPath($dom);
        $nodeList = $xpath->query('/global/templates/*');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $item = [];
            $node = $nodeList->item($i);
            foreach ($attributeNamesList as $name) {
                if ($node->hasAttribute($name)) {
                    $item[$name] = $node->getAttribute($name);
                }
            }
            $extractedData[] = $item;
        }

        return $extractedData;
    }
}
