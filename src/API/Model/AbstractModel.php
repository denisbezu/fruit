<?php

namespace Fruit\API\Model;

use JsonSerializable;

abstract class AbstractModel implements JsonSerializable
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $getterName = get_class_methods(get_class($this));
        $gettableAttributes = [];
        foreach ($getterName as $value) {
            if (substr($value, 0, 3) === 'get') {
                $gettableAttributes[lcfirst(substr($value, 3, strlen($value)))] = $this->$value();
            }
        }

        return $gettableAttributes;
    }

    /**
     * hydrate from array
     *
     * @param array<mixed> $content
     *
     * @return static
     */
    public function hydrate(array $content)
    {
        $setterName = get_class_methods(get_class($this));
        foreach ($setterName as $value) {
            if (substr($value, 0, 3) === 'set') {
                $key = lcfirst(substr($value, 3, strlen($value)));
                if (isset($content[$key])) {
                    $this->$value($content[$key]);
                }
            }
        }

        return $this;
    }
}
