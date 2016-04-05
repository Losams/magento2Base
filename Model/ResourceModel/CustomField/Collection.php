<?php namespace Zero\Base\Model\ResourceModel\CustomField;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Zero\Base\Model\CustomField', 'Zero\Base\Model\ResourceModel\CustomField');
    }
}
