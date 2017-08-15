<?php

declare(strict_types=1);

namespace ConstLab\DTO\Tests;

use ConstLab\DTO\Eloquent\EloquentDataTransferObject;
use ConstLab\DTO\Eloquent\EloquentDataTransferObjectInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BookModel
 *
 * @package ConstLab\DTO\Tests
 */
class BookModel extends Model implements EloquentDataTransferObjectInterface
{
    use EloquentDataTransferObject;

    protected $table = 'books';

    /**
     * @return string
     */
    public function getDataTransferObjectClass(): string
    {
        return Book::class;
    }
}