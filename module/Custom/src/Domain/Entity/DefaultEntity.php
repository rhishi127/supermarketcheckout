<?php

namespace Custom\Domain\Entity;

use Custom\Core\Entity\IEntity;
use ReflectionObject;
use ReflectionProperty;

class DefaultEntity implements IEntity
{
    public function exchangeArray($data)
    {
        $reflectionObject = new ReflectionObject($this);
        $protectedPropertyArray = $reflectionObject->
                getProperties(ReflectionProperty::IS_PROTECTED);
        foreach ($protectedPropertyArray as $reflectionPropertyItem) {
            $propertyName = $reflectionPropertyItem->getName();
            $this->$propertyName = isset($data[$propertyName])
                    ? $data[$propertyName] : null;
        }
    }

    public function toArray()
    {
        //only expose public property
        $reflectionObject = new ReflectionObject($this);
        $publicPropertyArray = $reflectionObject->getProperties(ReflectionProperty::IS_PROTECTED);
        $data = [];
        foreach ($publicPropertyArray as $reflectionPropertyItem) {
            $propertyName = $reflectionPropertyItem->getName();
            $data[$propertyName] = $this->$propertyName;
        }
        return $data;
    }
}
