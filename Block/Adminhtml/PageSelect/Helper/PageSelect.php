<?php
namespace Zero\Base\Block\Adminhtml\PageSelect\Helper;

use Magento\Framework\Data\Form\Element\Select as SelectField;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\CollectionFactory as ElementCollectionFactory;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

/**
 * @method string getValue()
 */
class PageSelect extends SelectField
{
    protected $collection;
    /**
     * @param SlideImage $imageModel
     * @param ElementFactory $factoryElement
     * @param ElementCollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        ElementFactory $factoryElement,
        \Magento\Cms\Model\PageFactory $collection,
        ElementCollectionFactory $factoryCollection,
        Escaper $escaper,
        $data = []
    )
    {
        $this->collection = $collection;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    protected function _prepareOptions()
    {
        // prepare options table
        $options = array();
        $options[''] = __('Selectionnez une page');

        // \Magento\Cms\Model\PageFactory
        $collection = $this->collection->create()->getCollection()
            ->addFieldToSelect('*')
            ->setOrder('title', 'asc');
        if (count($collection) > 0) {
            foreach ($collection as $item) {
                $options[] = ['value' => $item->getId(), 'label' => $item->getTitle()];
            }
        }

        $this->setValues($options);
    }
}
