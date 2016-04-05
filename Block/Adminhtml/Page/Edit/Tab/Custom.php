<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Zero\Base\Block\Adminhtml\Page\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Custom extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_reader;
    protected $_helperCustomField;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Zero\Base\Model\Pagefield\Reader $reader,
        \Zero\Base\Helper\CustomField $helperCustomField,
        array $data = []
    ) {
        $this->_reader = $reader;
        $this->_helperCustomField = $helperCustomField;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        // Get XML of custom template & custom field
        $configTab = $this->_helperCustomField->getCustomFieldConfig();
        $configTpl = $this->_helperCustomField->getCustomTplConfig();

        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('cms_page');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Magento_Cms::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');
        $htmlIdPrefix = $form->getHtmlIdPrefix();

        // Add fieldset for templating
        $fieldset = $form->addFieldset('template_fieldset', ['legend' => __('Template choice')]);

        $tplOptions = ['' => __('Aucun')];

        // Construct array options for template field (select)
        if ($configTpl) {
            foreach ($configTpl as $template) {
                if (isset($template['name']) && isset($template['path'])) {
                    $tplOptions[$template['name']] = $template['name'];
                }
            }
        }

        // Create template field
        $fieldset->addField(
            'template',
            'select',
            [
                'name' => 'template',
                'label' => __('Template de page'),
                'title' => __('Template de page'),
                'options' => $tplOptions
            ]
        );
        $datas['template'] = $this->_helperCustomField->getCustomField($model->getId(), 'template');

        // Treatment for adding field from XML to panel
        if ($configTab) {
            $tpl_relation = array();

            foreach ($configTab as $tab) {
                // Add fieldset for field group
                $fieldsetname = $tab['label'].'_fieldset';
                $fieldset = $form->addFieldset($fieldsetname, ['legend' => $tab['label']]);
                foreach ($tab['fields'] as $f) {
                    $fieldset->addField(
                        $f['name'],
                        $f['type'],
                        $this->_helperCustomField->getFieldOptions($f)
                    );

                    $datas[$f['name']] = $this->_helperCustomField->getCustomField($model->getid(), $f['name']);

                    if (isset($tab['template'])) {
                        $tpl_relation[$tab['template']][] = $f['name'];
                    }
                }
            }

            // Add dependancies template if needed
            if ($tpl_relation) {
                $dep =  $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence');
                $dep->addFieldMap("{$htmlIdPrefix}template", 'template');

                foreach ($tpl_relation as $template => $fields) {
                    foreach ($fields as $field) {
                        $dep->addFieldMap("{$htmlIdPrefix}{$field}", $field);
                        $dep->addFieldDependence($field, 'template', $template);
                    }
                }

                $this->setChild('form_after', $dep);
            }
        }

        $this->_eventManager->dispatch('adminhtml_cms_page_edit_tab_custom_prepare_form', ['form' => $form]);

        $form->setValues($datas);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Custom Fields');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Custom Fields');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
