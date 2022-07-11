<?php

namespace Fruit\API\Model;

use InvalidArgumentException;

/**
 * Best Price Model Class
 */
class Error extends AbstractModel
{
    // PROPERTIES

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var string|null
     */
    private $detail;

    /**
     * @var string|null
     */
    private $instance;

    /**
     * @var array|null
     */
    private $errors;

    // GETTERS & SETTERS

    /**
     * Get Type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Type
     *
     * @param string|null $type
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_string($type) === true || is_null($type) === true) {
            $this->type = $type;

            return $this;
        }

        throw new InvalidArgumentException('Type must be a string or null but ' . gettype($type) . ' is given.');
    }

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param string|null $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        if (is_string($title) === true || is_null($title) === true) {
            $this->title = $title;

            return $this;
        }

        throw new InvalidArgumentException('Title must be a string or null but ' . gettype($title) . ' is given.');
    }

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Status
     *
     * @param int|null $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_int($status) === true || is_null($status) === true) {
            $this->status = $status;

            return $this;
        }

        throw new InvalidArgumentException('Status must be an integer or null but ' . gettype($status) . ' is given.');
    }

    /**
     * Get Detail
     *
     * @return string|null
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set Detail
     *
     * @param string|null $detail
     *
     * @return self
     */
    public function setDetail($detail)
    {
        if (is_string($detail) === true || is_null($detail) === true) {
            $this->detail = $detail;

            return $this;
        }

        throw new InvalidArgumentException('Detail must be a string or null but ' . gettype($detail) . ' is given.');
    }

    /**
     * Get Instance
     *
     * @return string|null
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Set Instance
     *
     * @param string|null $instance
     *
     * @return self
     */
    public function setInstance($instance)
    {
        if (is_string($instance) === true || is_null($instance) === true) {
            $this->instance = $instance;

            return $this;
        }

        throw new InvalidArgumentException('Instance must be a string or null but ' . gettype($instance) . ' is given.');
    }

    /**
     * Get Errors
     *
     * @return array<mixed>|null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set Errors
     *
     * @param array<mixed>|null $errors
     *
     * @return self
     */
    public function setErrors($errors)
    {
        if (is_array($errors) === true || is_null($errors) === true) {
            $this->errors = $errors;

            return $this;
        }

        throw new InvalidArgumentException('Errors must be an array or null but ' . gettype($errors) . ' is given.');
    }
}
