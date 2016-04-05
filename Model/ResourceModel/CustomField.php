<?php
namespace Zero\Base\Model\ResourceModel;

class CustomField extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('zero_custom_field', 'id');
    }
}
