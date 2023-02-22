<?php

namespace src;

class Cursor
{
    public ?Cursor $parent = null;

    /**
     * @var mixed
     */
    public $param;
    /**
     * @var mixed
     */
    public $class;
    /**
     * @var mixed
     */
    public $value;

    /**
     * @param mixed $param
     * @param mixed $class
     * @param mixed $value
     */
    public function __construct($param, $class, $value = null)
    {
        $this->param = $param;
        $this->class = $class;
        $this->value = $value;
    }
}
