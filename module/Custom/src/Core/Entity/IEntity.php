<?php
namespace Custom\Core\Entity;

interface IEntity
{
    //get array representation of the object
    public function toArray();

    //convert array representation to object
    public function exchangeArray($data);
}
