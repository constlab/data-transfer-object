<?php

declare(strict_types=1);

namespace ConstLab\DTO\Tests;

use ConstLab\DTO\DataTransferObject;

/**
 * Class Book
 *
 * @package ConstLab\DTO\Tests
 *
 * @property int $id
 * @property string $title
 * @property Author $author
 */
class Book extends DataTransferObject
{
    protected $id;
    protected $title;
    protected $author;

    /**
     * @return mixed
     */
    protected function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    protected function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    protected function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    protected function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    protected function getId()
    {
        return $this->id + 1;
    }

    /**
     * @param mixed $id
     */
    protected function setId($id)
    {
        $this->id = $id;
    }
}