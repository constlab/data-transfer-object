<?php

declare(strict_types=1);

namespace ConstLab\DTO\Tests;

use ConstLab\DTO\DataTransferObject;
use ConstLab\DTO\Eloquent\EloquentDataTransferObjectInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use JsonSerializable;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Class EloquentDataTransferObjectTest
 *
 * @package ConstLab\DTO\Tests
 */
class EloquentDataTransferObjectTest extends TestCase
{
    /**
     * @var Capsule
     */
    protected $db;

    /**
     * Create database and dummy data
     */
    public function setUp()
    {
        parent::setUp();

        $capsule = new Capsule(null);
        $capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'charset'  => 'utf8',
            'prefix'   => '',
            'fetch'    => PDO::FETCH_CLASS
        ]);
        $capsule->setFetchMode(PDO::FETCH_CLASS);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->db = $capsule;

        $this->createTables();
        $this->createDummyData();
    }

    /**
     * Create database tables
     */
    protected function createTables()
    {
        $this->db::schema()->create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('author');
        });
    }

    /**
     * Create dummy data
     */
    protected function createDummyData()
    {
        $this->db::table('books')->insert([
            ['title' => 'The Adventures of Tom Sawyer', 'author' => 'Mark Twain'],
            ['title' => 'The Call of the Wild', 'author' => 'Jack London'],
        ]);
    }

    /**
     * Create model test
     */
    public function testEloquentModel()
    {
        $model = new BookModel();

        $this->assertInstanceOf(Model::class, $model);
        $this->assertInstanceOf(EloquentDataTransferObjectInterface::class, $model);
    }

    /**
     * Test for transform model to dto
     */
    public function testModelToDto()
    {
        $model = BookModel::query()->first();
        $dto = $model->toDto();

        $this->assertNotEmpty($model->getDataTransferObjectClass());
        $this->assertInstanceOf(DataTransferObject::class, $dto);
        $this->assertInstanceOf(JsonSerializable::class, $dto);
        $this->assertInstanceOf(Jsonable::class, $dto);
        $this->assertInstanceOf(Arrayable::class, $dto);
        $this->assertInstanceOf(Book::class, $dto);

        $this->assertObjectHasAttribute('id', $dto);
        $this->assertObjectHasAttribute('title', $dto);

        $this->assertAttributeNotEmpty('id', $dto);
        $this->assertAttributeNotEmpty('title', $dto);

        $this->assertAttributeInternalType('int', 'id', $dto);
        $this->assertAttributeInternalType('string', 'title', $dto);

        $this->assertEquals(2, $dto->id);
        $this->assertEquals('The Adventures of Tom Sawyer', $dto->title);
    }

}