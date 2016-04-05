<?php
namespace Zero\Base\Model\Pagefield;
class SchemaLocator implements \Magento\Framework\Config\SchemaLocatorInterface
{
    /**
     * Get path to merged config schema
     *
     * @return string
     */
    public function getSchema()
    {
        return realpath(__DIR__ . '/../../etc/pagefield.xsd');
    }
    /**
     * Get path to pre file validation schema
     *
     * @return null
     **/
    public function getPerFileSchema()
    {
        return null;
    }
}
