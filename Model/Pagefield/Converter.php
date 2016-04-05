<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zero\Base\Model\Pagefield;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @param mixed $dom
     * @return array
     */
    public function convert($dom)
    {
        $extractedData = [];

        $attributeNamesListTab = [
            'label',
            'template',
        ];

        $attributeNamesListField = [
            'name',
            'type',
            'label',
            'required',
        ];

        $xpath = new \DOMXPath($dom);
        $nodeList = $xpath->query('/global/*');
        for ($i = 0; $i < $nodeList->length; $i++) {
            $item = [];
            $node = $nodeList->item($i);
            foreach ($attributeNamesListTab as $name) {
                if ($node->hasAttribute($name)) {
                    $item[$name] = $node->getAttribute($name);

                    $fields = $xpath->query('.//fields', $node);
                    foreach ($fields as $key => $f) {
                        foreach ($attributeNamesListField as $name) {
                            $item['fields'][$key][$name] = $f->getAttribute($name);
                        }
                    }
                }
            }
            $extractedData[] = $item;
        }

        return $extractedData;
    }
}
