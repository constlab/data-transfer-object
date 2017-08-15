<?php

declare(strict_types=1);

namespace ConstLab\DTO\Tests;

use ConstLab\DTO\DataTransferObject;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

/**
 * Class DataTransferObjectTest
 */
class DataTransferObjectTest extends TestCase
{

    /**
     * Tests for object attributes
     *
     * @param Book $book
     */
    private function assertAttributes(Book $book)
    {
        $this->assertObjectHasAttribute('id', $book);
        $this->assertObjectHasAttribute('title', $book);

        $this->assertAttributeNotEmpty('id', $book);
        $this->assertAttributeNotEmpty('title', $book);

        $this->assertAttributeInternalType('int', 'id', $book);
        $this->assertAttributeInternalType('string', 'title', $book);

        $this->assertEquals(2, $book->id);
        $this->assertEquals('The Adventures of Tom Sawyer', $book->title);
    }

    /**
     * Test for creating dto
     */
    public function testObject()
    {
        $book = new Book();

        $this->assertInstanceOf(DataTransferObject::class, $book);
        $this->assertInstanceOf(JsonSerializable::class, $book);
        $this->assertInstanceOf(Jsonable::class, $book);
        $this->assertInstanceOf(Arrayable::class, $book);
    }

    /**
     * Test for magic attributes
     */
    public function testAttributes()
    {
        $book        = new Book();
        $book->id    = 1;
        $book->title = 'The Adventures of Tom Sawyer';

        $this->assertAttributes($book);
    }

    /**
     * Test for setter attributes via constructor
     */
    public function testConstructorAttributes()
    {
        $book = new Book(['id' => 1, 'title' => 'The Adventures of Tom Sawyer']);

        $this->assertAttributes($book);
    }

    /**
     * Test for creating dto with array data via static method
     */
    public function testMakeArrayAttributes()
    {
        $book = Book::make(['id' => 1, 'title' => 'The Adventures of Tom Sawyer']);

        $this->assertAttributes($book);
    }

    /**
     * Test for creating dto with array data via static method
     */
    public function testMakeObjectAttributes()
    {
        $object        = new \stdClass();
        $object->id    = 1;
        $object->title = 'The Adventures of Tom Sawyer';

        $book = Book::make($object);

        $this->assertAttributes($book);
    }

    /**
     * Test for creating dto with closure
     */
    public function testMakeClosure()
    {
        $book = Book::make(function (Book $book) {
            $book->id    = 1;
            $book->title = 'The Adventures of Tom Sawyer';

            return $book;
        });

        $this->assertAttributes($book);
    }

    /**
     * Test for checking transform object to array and json string
     */
    public function testTransformObject()
    {
        $book = Book::make(['id' => 1, 'title' => 'The Adventures of Tom Sawyer']);

        $this->assertInternalType('array', $book->toArray());
        $this->assertInternalType('array', $book->jsonSerialize());

        $this->assertJson($book->toJson());
        $this->assertJson((string)$book);
    }
}