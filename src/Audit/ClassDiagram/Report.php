<?php

namespace RodrigoRM\Audit\ClassDiagram;

use RodrigoRM\Audit\Report as ReportInterface;

use Alom\Graphviz\Digraph;

class Report implements ReportInterface
{
    private $graph;

    public function __construct(Digraph $graph)
    {
        $this->graph = $graph;
    }

    public function write($filename)
    {
        file_put_contents($filename, $this->graph->render());
    }
}
