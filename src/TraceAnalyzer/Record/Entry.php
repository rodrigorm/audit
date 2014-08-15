<?php

namespace TraceAnalyzer\Record;

use TraceAnalyzer\Record;

class Entry implements Record
{
    private $level;
    private $stack;
    private $time;
    private $memory;
    private $function;
    private $userDefined;
    private $include;
    private $filename;
    private $line;

    public function __construct(
        $level,
        $stack,
        $time,
        $memory,
        $function,
        $userDefined,
        $include,
        $filename,
        $line
    ) {
        $this->level = $level;
        $this->stack = $stack;
        $this->time = $time;
        $this->memory = $memory;
        $this->function = $function;
        $this->userDefined = $userDefined;
        $this->include = $include;
        $this->filename = $filename;
        $this->line = $line;
    }
}
