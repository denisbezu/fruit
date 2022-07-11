<?php

namespace Fruit\API\Response;

use Fruit\API\Model\ArrayCollection;
use Fruit\API\Model\Error;
use Fruit\API\Model\Fruit\FruitModel;
use InvalidArgumentException;

class FruitsResponse extends AbstractResponse
{
    public function getModel()
    {
        $content = (string) $this->stream;
        if (empty($content) === true) {
            return new ArrayCollection();
        }
        $output = json_decode($content, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException('json_decode error: ' . json_last_error_msg());
        }
        if (empty($output) === true) {
            return new ArrayCollection();
        }

        if ($this->getStatusCode() < 200 || $this->getStatusCode() > 299) {
            return (new Error())->hydrate($output);
        }

        $collection = new ArrayCollection();

        foreach ($output as $item) {
            $collection->append((new FruitModel())->hydrate($item));
        }

        return $collection;
    }
}
