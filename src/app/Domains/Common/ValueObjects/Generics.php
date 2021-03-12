<?php

declare(strict_types=1);

namespace App\Domains\Common\ValueObjects;

use Countable;
use InvalidArgumentException;
use Iterator;

class Generics implements Iterator, Countable
{
    protected string $keyType;
    protected string $valueType;
    protected array $keys;
    protected array $values;
    protected int $pointer;

    /**
     * @return static
     */
    public static function create(string $keyType, string $valueType, array $keys = [], array $values = [])
    {
        return new static($keyType, $valueType, $keys, $values);
    }

    protected function __construct(string $keyType, string $valueType, array $keys = [], array $values = [])
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
        $this->keys = $keys;
        $this->values = $values;
        $this->pointer = 0;
    }

    /**
     * @see https://www.php.net/manual/en/iterator.current.php
     */
    public function current()
    {
        return $this->values[$this->pointer];
    }

    /**
     * @see https://www.php.net/manual/en/iterator.key.php
     */
    public function key()
    {
        return $this->keys[$this->pointer];
    }

    /**
     * @see https://www.php.net/manual/en/iterator.next.php
     */
    public function next(): void
    {
        ++$this->pointer;
    }

    /**
     * @see https://www.php.net/manual/en/iterator.rewind.php
     */
    public function rewind(): void
    {
        $this->keys = array_values($this->keys);
        $this->values = array_values($this->values);

        $this->pointer = 0;
    }

    /**
     * @see https://www.php.net/manual/en/iterator.valid.php
     */
    public function valid(): bool
    {
        return isset($this->values[$this->pointer]);
    }

    /**
     * @see https://www.php.net/manual/en/countable.count.php
     */
    public function count(): int
    {
        return count($this->values);
    }

    public function keyType(): string
    {
        return $this->keyType;
    }

    public function valueType(): string
    {
        return $this->valueType;
    }

    public function keys(): array
    {
        return $this->keys;
    }

    public function values(): array
    {
        return $this->values;
    }

    public function exists($offset): bool
    {
        $key = array_search($offset, $this->keys, true);

        return isset($this->values[$key]);
    }

    /**
     * @return mixed
     */
    public function get($offset)
    {
        $key = array_search($offset, $this->keys, true);

        return $this->values[$key] ?? null;
    }

    public function set($offset, $value): void
    {
        if (!$this->typeCheck($this->keyType, $offset)) {
            $type = is_object($offset) ? get_class($offset) : gettype($offset);

            throw new InvalidArgumentException(sprintf('`%s` is invalid key type.', $type));
        }

        if (!$this->typeCheck($this->valueType, $value)) {
            $type = is_object($value) ? get_class($value) : gettype($value);

            throw new InvalidArgumentException(sprintf('`%s` is invalud value valueType.', $type));
        }

        $this->keys[] = $offset;
        $this->values[] = $value;
    }

    public function unset($offset): void
    {
        $key = array_search($offset, $this->keys, true);

        unset($this->keys[$key]);
        unset($this->values[$key]);
    }

    protected function typeCheck(string $type, $value): bool
    {
        switch ($type) {
            case 'bool':
                return is_bool($value);
            case 'float':
                return is_float($value);
            case 'int':
                return is_int($value);
            case 'string':
                return is_string($value);
            case 'array':
                return is_array($value);
            case 'callable':
                return is_callable($value);
            case 'iterable':
                return is_iterable($value);
            case 'null':
                return is_null($value);
            default:
                return $value instanceof $type;
        }
    }
}
