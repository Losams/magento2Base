<?php
namespace Zero\Base\Helper;

class CustomField extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_objectManager;
    protected $_reader;
    protected $_readerTpl;

    private $config = null;
    private $configTpl = null;

    function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Zero\Base\Model\Pagefield\Reader $reader,
        \Zero\Base\Model\Pagefield\ReaderTpl $readerTpl,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->_reader = $reader;
        $this->_readerTpl = $readerTpl;
        parent::__construct($context);
    }

    /**
     * Function getter of XML to add templates in page CMS
     */
    public function getCustomTplConfig()
    {
        if (!$this->configTpl) {
            $this->configTpl = $this->_readerTpl->read();
        }
        return $this->configTpl;
    }

    /**
     * Function getter of XML to add fields in page CMS
     */
    public function getCustomFieldConfig()
    {
        if (!$this->config) {
            $this->config = $this->_reader->read();
        }
        return $this->config;
    }

    /**
     * Return the path (like Zero_Base::test.phtml) of the pageId
     * @param pageId (int)
     * @return string / null
     */
    public function getTemplatePath($pageId) {
        // Get page object
        $model = $this->_objectManager->create('\Zero\Base\Model\CustomField');
        $modelCustomField = $model->getCollection()
            ->addFieldToFilter('entity_id', $pageId)
            ->addFieldToFilter('name', 'template')
            ;

        // if page existe
        if (count($modelCustomField)) {
            $modelCustomField = $modelCustomField->getFirstItem();
            $value = $modelCustomField->getValue();
            $config = $this->getCustomTplConfig();

            // Search for his template in current file (not stocked in BDD to keep the flexibility)
            foreach ($config as $template) {
                if ($template && $template['name'] == $value && $template['path']) {
                    return $template['path'];
                }
            }
        }

        return null;
    }

    /**
     * Get the value of fieldName for pageId object saved
     * If fieldName null, return all customs value for pageId
     */
    public function getCustomField($pageId, $fieldName = null) {
        $model = $this->_objectManager->create('\Zero\Base\Model\CustomField');
        $modelCustomField = $model->getCollection()->addFieldToFilter('entity_id', $pageId);

        if ($fieldName) {
            $modelCustomField->addFieldToFilter('name', $fieldName);
        }

        // if row
        if (count($modelCustomField)) {
            if ($fieldName) {
                $modelCustomField = $modelCustomField->getFirstItem();
                return $modelCustomField->getValue();
            }
            return $modelCustomField;
        }

        return null;
    }

   /**
    * Create options array for field generation in form
    */
    public function getFieldOptions($field)
    {
        $return = [
            'name' => $field['name'],
            'label' => $field['label'],
            'title' => $field['label'],
            'required' => $field['required'] ? true : false,
        ];

        // Options for select type
        if (isset($field['option'])) {
            $options = [];
            foreach ($field['option'] as $option) {
                $options[$option['value']] = $option['label'];
            }
            $return['options'] = $options;
        }

        return $return;
    }
}
