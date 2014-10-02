<?php

namespace RodrigoRM\Audit\ClassDiagram;

use RodrigoRM\Audit\Builder as BuilderInterface;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;
use RodrigoRM\Audit\Record\End;
use RodrigoRM\Audit\Record\Result;
use RodrigoRM\Audit\ClassDiagram\Report;

use Alom\Graphviz\Digraph;

class Builder implements BuilderInterface
{
    private $namespace = null;
    private $stack = array();
    private $graph = array();

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

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
        $className = $record->className();

        $previous = end($this->stack);
        $this->stack[] = $className;

        if ($className === $previous) {
            return;
        }

        if ($this->namespace && strpos($className, $this->namespace) !== 0) {
            return;
        }

        if (empty($this->graph[$className])) {
            $this->graph[$className] = array();
        }

        if (empty($previous)) {
            return;
        }

        if ($this->namespace && strpos($previous, $this->namespace) !== 0) {
            return;
        }

        $this->graph[$previous][$className] = true;
    }

    public function addLeaveRecord(Leave $record)
    {
        array_pop($this->stack);
    }

    public function addEndRecord(End $record)
    {
    }

    public function addReturnRecord(Result $record)
    {
    }

    public function traceEnd(\DateTime $end)
    {
    }

    /**
     * @return RodrigoRM\Audit\Report
     */
    public function build()
    {
        return new Report($this->buildGraph());
    }

    /**
     * @return Alom\Graphviz\Digraph
     */
    private function buildGraph()
    {
        $graph = new Digraph('class_diagram');

        foreach ($this->graph as $dependent => $dependencies) {
            $graph->node($dependent);
            foreach (array_keys($dependencies) as $dependency) {
                $graph->edge(array($dependent, $dependency));
            }
        }

        return $graph;
    }
}
