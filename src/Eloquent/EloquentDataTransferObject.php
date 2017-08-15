<?php

declare(strict_types=1);

namespace ConstLab\DTO\Eloquent;

use ConstLab\DTO\DataTransferObject;

/**
 * Trait EloquentDataTransferObject
 *
 * @package ConstLab\DTO\Eloquent
 */
trait EloquentDataTransferObject
{
    /**
     * Transform model to DTO
     *
     * @return DataTransferObject|null
     */
    public function toDto()
    {
        if (empty($dtoClass = $this->getDataTransferObjectClass())) {
            return null;
        }

        return new $dtoClass($this->toArray());
    }
}