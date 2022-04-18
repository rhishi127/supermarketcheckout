<?php

namespace Custom\Utility\Hydrate;

use Custom\Domain\Entity\DefaultEntity;

class GenericEntityHydrator
{
    public function hydrate($originalArray, $rowPrototype)
    {
        /**
         * @var DefaultEntity
         */
        $rowPrototypeEntity = new $rowPrototype;
        $rowPrototypeEntity->exchangeArray($originalArray);
        return $rowPrototypeEntity;
    }
}
