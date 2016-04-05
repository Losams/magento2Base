<?php

namespace Zero\Base\Model\Pagefield;

class ReaderTpl extends \Magento\Framework\Config\Reader\Filesystem
{
    protected $_idAttributes = [
        '/global/templates' => 'id',
    ];

    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Zero\Base\Model\Pagefield\ConverterTpl $converter,
        \Zero\Base\Model\Pagefield\SchemaLocatorTpl $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        $fileName = 'pagetemplate.xml',
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
