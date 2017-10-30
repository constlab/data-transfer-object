<?php

declare(strict_types=1);

namespace ConstLab\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DataTransferObject
 *
 * @package ConstLab\DTO
 */
abstract class DataTransferObject implements \JsonSerializable, Jsonable, Arrayable
{

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * DataTransferObject constructor.
     *
     * @param array $data
     * @throws \BadMethodCallException
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * Create new DTO with attributes
     *
     * @param array|\stdClass|Model|\Closure $data
     *
     * @return DataTransferObject|mixed
     * @throws \BadMethodCallException
     */
    public static function make($data)
    {
        if (is_array($data)) {
            return new static($data);
        }

        if ($data instanceof \stdClass) {
            return new static((array)$data);
        }

        if ($data instanceof Model) {
            return new static($data->toArray());
        }

        if ($data instanceof \Closure) {
            return $data(new static());
        }

        return new static();
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __get(string $name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        $getter = 'is' . ucfirst($name);
        if(method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \BadMethodCallException("Method {$getter} not found in class " . get_class($this));
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @throws \BadMethodCallException
     */
    public function __set(string $name, $value)
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        }

        throw new \BadMethodCallException("Method {$setter} not found in class " . get_class($this));
    }

    /**
     * @param string $name
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes()[$name]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function attributes(): array
    {
        if (empty($this->attributes)) {
            $class = new \ReflectionClass($this);
            $pattern = '/^(get|is)[[:upper:]]{1}(\S)+/';
            $methods = $class->getMethods(\ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $name = $method->getName();
                if (preg_match($pattern, $name)) { //starts_with('get' or 'is', $name)
                    $name = preg_replace('/^(get|is)/', '', $name);
                    $this->attributes[] = lcfirst($name);
                }
            }
        }

        return $this->attributes;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->attributes() as $attribute) {
            $result[$attribute] = $this->__get($attribute);
        }

        return $result;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     * @throws \ReflectionException
     * @throws \BadMethodCallException
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * @throws \ReflectionException
     * @throws \BadMethodCallException
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}