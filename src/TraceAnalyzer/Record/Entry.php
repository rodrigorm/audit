<?php

namespace TraceAnalyzer\Record;

use TraceAnalyzer\Record;

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

    public function __construct(
        $depth,
        $stack,
        $time,
        $memory,
        $function,
        $userDefined,
        $include,
        $filename,
        $line
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
