<?php
namespace Zero\Base\Api\Data;


interface CustomFieldInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const NAME = 'name';
    const VALUE = 'value';
    const ENTITYID = 'entity_id';

    public function getId();

    public function getName();

    public function getValue();

    public function getEntityId();

    public function setId($id);

    public function setName($name);

    public function setEntityId($entityId);

    public function setValue($value);

    public function getIdentities();

}
