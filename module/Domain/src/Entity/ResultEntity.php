<?php

namespace Domain\Entity;

class ResultEntity
{
    /**
     * @var bool
     */
    public $is_success;

    public $result;

    /**
     * @return bool
     */
    public function getIsSuccess(): bool
    {
        return $this->is_success;
    }

    /**
     * @param bool $is_success
     */
    public function setIsSuccess(bool $is_success): void
    {
        $this->is_success = $is_success;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }
}
