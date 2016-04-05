<?php
namespace Zero\Base\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Zero\Base\Api\Data\CustomFieldInterface;

class CustomField extends \Magento\Framework\Model\AbstractModel implements CustomFieldInterface, IdentityInterface
{
    const CACHE_TAG = 'zero_custom_field';

    protected function _construct()
    {
        $this->_init('Zero\Base\Model\ResourceModel\CustomField');
    }

    /* Getters */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function getEntityId()
    {
        return $this->getData(self::ENTITYID);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /* Setters */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITYID, $entityId);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
