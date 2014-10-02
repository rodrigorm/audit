<?php

namespace RodrigoRM\Audit\Record;

use RodrigoRM\Audit\Record;

class Entry implements Record
{
    private $depth;
    private $stack;
    private $time;
    private $memory;
    private $function;
    private $userDefined;
    private $include;
    private $filename;
    private $line;
    private $arguments = array();

    public function __construct(
        $depth,
        $stack,
        $time,
        $memory,
        $function,
        $userDefined,
        $include,
        $filename,
        $line,
        $arguments = array()
    ) {
        $this->depth = $depth;
        $this->stack = $stack;
        $this->time = $time;
        $this->memory = $memory;
        $this->function = $function;
        $this->userDefined = $userDefined;
        $this->include = $include;
        $this->filename = $filename;
        $this->line = $line;
        $this->arguments = $arguments;
    }

    public function className()
    {
        if (strpos($this->function, '->') !== false) {
            list($className, ) = explode('->', $this->function);
            return $className;
        }

        if (strpos($this->function, '::') !== false) {
            list($className, ) = explode('::', $this->function);
            return $className;
        }

        return '';
    }
}
