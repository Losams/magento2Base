<?php

namespace Zero\Base\Observer;

use Magento\Framework\Event\ObserverInterface;

class EditPageCustomField implements ObserverInterface
{
    /**
     * Cms page
     *
     * @var \Magento\Cms\Helper\Page
     */
    protected $_cmsPage;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    protected $_reader;
    protected $_objectManager;

    /**
     * @param \Magento\Cms\Helper\Page $cmsPage
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Cms\Helper\Page $cmsPage,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Zero\Base\Model\Pagefield\Reader $reader,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_cmsPage = $cmsPage;
        $this->_objectManager = $objectManager;
        $this->_reader = $reader;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Modify no Cookies forward object
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $configTab = $this->_reader->read();
        $page = $observer->getEvent()->getPage();
        $request = $observer->getEvent()->getRequest();

        $data = $request->getPostValue();

        // Save template
        $this->saveCustomField($page->getId(), 'template', $data['template']);

        // Foreach config input custom
        if ($configTab) {
            foreach ($configTab as $tab) {
                foreach ($tab['fields'] as $f) {
                    // Check if input is POST
                    if (isset($data[$f['name']])) {
                        $this->saveCustomField($page->getId(), $f['name'], $data[$f['name']]);
                    }
                }
            }
        }

        return $this;
    }

    public function saveCustomField($page, $name, $value)
    {
        $model = $this->_objectManager->create('\Zero\Base\Model\CustomField');

        // Check if row exist in BDD
        if ($page) {
            $modelCustomField = $model->getCollection()
                ->addFieldToFilter('entity_id', $page)
                ->addFieldToFilter('name', $name)
                ;

            // if row
            if (count($modelCustomField)) {
                $modelCustomField = $modelCustomField->getFirstItem();
                $model = $modelCustomField->load($modelCustomField->getId());
            } else {
                // else we set attribut
                $model->setEntityId($page);
                $model->setName($name);
            }
        } else { // If no row on BDD
            $model->setEntityId($page);
            $model->setName($name);
        }

        // Set final value on model
        $model->setValue($value);
        $model->save();
    }
}
