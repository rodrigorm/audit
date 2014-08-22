<?php

namespace TraceAnalyzer\Summary;

use TraceAnalyzer\Builder as BuilderInterface;
use TraceAnalyzer\Record\Entry;
use TraceAnalyzer\Record\Leave;
use TraceAnalyzer\Record\End;

class Builder implements BuilderInterface
{
    private $stack = array();
    private $functions = array();

    public function setVersion($version)
    {
    }

    public function setFileFormat($format)
    {
    }

    public function traceStart(\DateTime $start)
    {
    }

    public function addEntryRecord(Entry $record)
    {
        $this->stack[$record->getDepth()] = array(
            'entry' => $record,
            'time-children' => 0,
            'memory-children' => 0
        );
    }

    public function addLeaveRecord(Leave $record)
    {
        list($entry, $nestedTime, $nestedMemory) = array_values($this->stack[$record->getDepth()]);

        $time = $record->getTime() - $entry->getTime();
        $memory = $record->getMemory() - $entry->getMemory();

        if ($record->getDepth() > 1) {
            $this->stack[$record->getDepth() - 1]['time-children'] += $time;
            $this->stack[$record->getDepth() - 1]['memory-children'] += $memory;
        }

        if (!isset($this->functions[$entry->getFunctionName()])) {
            $this->functions[$entry->getFunctionName()] = array(
                'name' => $entry->getFunctionName(),
                'calls' => 0,
                'time-inclusive' => 0,
                'memory-inclusive' => 0,
                'time-children' => 0,
                'memory->children' => 0,
                'time-own' => 0,
                'memory-own' => 0
            );
        }

        $function = $this->functions[$entry->getFunctionName()];

        $function['calls']++;
        $function['time-inclusive'] += $time;
        $function['memory-inclusive'] += $memory;
        $function['time-cildren'] = $nestedTime;
        $function['memory-children'] = $nestedMemory;
        $function['time-own'] = $function['time-inclusive'] - $function['time-children'];
        $function['memory-own'] = $function['memory-inclusive'] - $function['memory-children'];

        $this->functions[$entry->getFunctionName()] = $function;
    }

    public function addEndRecord(End $record)
    {
    }

    public function traceEnd(\DateTime $end)
    {
    }

    public function functionsSortedBy($sortKey)
    {
        $functions = $this->functions;
        $sorted = array();

        foreach ($functions as $function) {
            $sorted[] = $function[$sortKey];
        }

        array_multisort($sorted, SORT_DESC, $functions);
        return $functions;
    }
}
