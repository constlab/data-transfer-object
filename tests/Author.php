<?php

declare(strict_types=1);

namespace ConstLab\DTO\Tests;

use ConstLab\DTO\DataTransferObject;

/**
 * Class Author
 *
 * @package ConstLab\DTO\Tests
 */
class Author extends DataTransferObject
{
    protected $name = '';

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;
    }
}