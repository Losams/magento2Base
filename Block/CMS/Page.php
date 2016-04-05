<?php
namespace Zero\Base\Block\CMS;

class Page extends \Magento\Cms\Block\Page
{
    protected $_filterProvider;

    protected $_page;

    protected $_storeManager;

    protected $_pageFactory;

    protected $pageConfig;

    protected $_helperCustomField;

    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Page $page,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\View\Page\Config $pageConfig,
        \Zero\Base\Helper\CustomField $helperCustomField,
        array $data = []
    ) {
        parent::__construct($context,$page, $filterProvider, $storeManager, $pageFactory,$pageConfig, $data);
        $this->_helperCustomField = $helperCustomField;
        $this->_objectManager = $objectManager;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        $pageMainTitle->setPageTitle(' ');
    }

    protected function _toHtml()
    {
        $request = $this->getRequest();
        $id      = (int) $request->getParam('page_id');

        // if homepage, id = 0, so we have to get page by slug 'home' to find the right id
        if($id == 0) {
            $homepage = $this->_pageFactory->create()->load(array('slug' => 'home'));
            $id = $homepage->getId();
        }

        $fieldsCollection = $this->_helperCustomField->getCustomField($id);
        $fields = [];

        if(count($fieldsCollection)) {
            foreach($fieldsCollection as $f) {
                $fields[$f->getName()] = $f->getValue();
            }
        }

        $template = $this->_helperCustomField->getTemplatePath($this->getPage()->getId());

        if ($template) {
            $cmsTitle = $this->getPage()->getContentHeading() ?: ' ';
            $html = $this->getLayout()->createBlock('Magento\Framework\View\Element\Template')
                ->setContent($this->getPage()->getContent())
                ->setTitle($cmsTitle)
                ->setFields($fields)
                ->setTemplate($template)->toHtml();
            return $html;
        }
        return parent::_toHtml();
    }
}
