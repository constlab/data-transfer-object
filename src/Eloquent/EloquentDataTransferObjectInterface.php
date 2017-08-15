<?php

declare(strict_types=1);

namespace ConstLab\DTO\Eloquent;

/**
 * Interface EloquentDataTransferObjectInterface
 *
 * @package ConstLab\DTO\Eloquent
 */
interface EloquentDataTransferObjectInterface
{
    /**
     * @return string
     */
    public function getDataTransferObjectClass(): string;
}