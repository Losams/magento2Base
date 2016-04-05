<?php

namespace Zero\Base\Model\Pagefield;

class Reader extends \Magento\Framework\Config\Reader\Filesystem
{
    protected $_idAttributes = [
        '/global/tabs' => 'id',
    ];

    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Zero\Base\Model\Pagefield\Converter $converter,
        \Zero\Base\Model\Pagefield\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        $fileName = 'pagefield.xml',
        $idAttributes = [],
        $domDocumentClass = 'Magento\Framework\Config\Dom',
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
