<?php

namespace App\Entities;

use RuntimeException;

abstract class Entity
{
    /**
     * The entity's attribute keys.
     *
     * @var array
     */
    protected $keys = [];

    /**
     * The entitye's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create an entity instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->attributes = array_map(function () {
            return null;
        }, array_flip($this->keys));
    }

    /**
     * Get whether it has the specified property.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Get the value of the specified property.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if (! $this->has($key)) {
            throw new RuntimeException(
                sprintf('Undefined property: %s::$%s', static::class, $key)
            );
        }

        return $this->attributes[$key];
    }

    /**
     * Set the value of the specified property.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, $value)
    {
        if (! $this->has($key)) {
            throw new RuntimeException(
                sprintf('Undefined property: %s::$%s', static::class, $key)
            );
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Convert the entity instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}
