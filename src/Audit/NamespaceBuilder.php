<?php

namespace RodrigoRM\Audit;

use RodrigoRM\Audit\Builder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;

class NamespaceBuilder implements Builder
{
    private $builder;
    private $namespaces = [];
    private $stack = array();

    public function __construct(Builder $builder, array $namespaces)
    {
        $this->builder = $builder;
        $this->namespaces = $namespaces;
    }

    public function setVersion($version)
    {
        return $this->builder->setVersion($version);
    }

    public function setFileFormat($format)
    {
        return $this->builder->setFileFormat($format);
    }

    public function traceStart(\DateTime $start)
    {
        return $this->builder->traceStart($start);
    }

    public function addEntryRecord(Entry $record)
    {
        $className = $record->className();
        $this->stack[] = $className;

        if ($this->skipNamespace($className)) {
            return;
        }

        return $this->builder->addEntryRecord($record);
    }

    public function addLeaveRecord(Leave $record)
    {
        $className = array_pop($this->stack);

        if ($this->skipNamespace($className)) {
            return;
        }

        return $this->builder->addLeaveRecord($record);
    }

    public function addEndRecord(End $record)
    {
        return $this->builder->addEndRecord($record);
    }

    public function traceEnd(\DateTime $end)
    {
        return $this->builder->traceEnd($end);
    }

    public function build()
    {
        return $this->builder->build();
    }

    private function skipNamespace($className)
    {
        if (empty($this->namespaces)) {
            return false;
        }

        foreach ($this->namespaces as $namespace) {
            if (strpos($className, $namespace) === 0) {
                return false;
            }
        }

        return true;
    }
}
