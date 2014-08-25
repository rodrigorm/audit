<?php

namespace RodrigoRM\Audit\ClassDiagram;

use RodrigoRM\Audit\ClassDiagram\Builder;
use RodrigoRM\Audit\Record\Entry;
use RodrigoRM\Audit\Record\Leave;

use Alom\Graphviz\Digraph;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;

    public function testShouldCreateClassDiagram()
    {
        $this->givenABuilder();
        $this->whenIAddAnEntry(0, 'AnNamespace\AnClass->anMethod');
        $this->whenIAddAnEntry(1, 'AnNamespace\AnotherClass->anMethod');
        $this->assertSimpleGraph();
    }

    public function testShouldSkipEntryWithoutClassName()
    {
        $this->givenABuilder();
        $this->whenIAddAnEntry(0, 'an_function');
        $this->whenIAddAnEntry(0, 'AnNamespace\AnClass->anMethod');
        $this->whenIAddAnEntry(1, 'AnNamespace\AnotherClass->anMethod');
        $this->assertSimpleGraph();
    }

    public function testShouldNotRegisterClassUsingHimself()
    {
        $this->givenABuilder();
        $this->whenIAddAnEntry(0, 'AnNamespace\AnClass->anMethod');
        $this->whenIAddAnEntry(1, 'AnNamespace\AnClass->anotherMethod');
        $this->whenIAddALeave(1);
        $this->whenIAddAnEntry(1, 'AnNamespace\AnotherClass->anMethod');
        $this->assertSimpleGraph();
    }

    public function testShouldSkipClassFromAnotherNamespace()
    {
        $this->givenABuilder();
        $this->whenIAddAnEntry(0, 'AnNamespace\AnClass->anMethod');
        $this->whenIAddAnEntry(1, 'AnotherNamespace\AnClass->anMethod');
        $this->whenIAddAnEntry(2, 'AnNamespace\AnClass->anotherMethod');
        $this->whenIAddALeave(2);
        $this->whenIAddALeave(1);
        $this->whenIAddAnEntry(1, 'AnNamespace\AnotherClass->anMethod');
        $this->assertSimpleGraph();
    }

    public function testShouldShowIsolatedNodes()
    {
        $this->givenABuilder();
        $this->whenIAddAnEntry(0, 'AnNamespace\AnClass->anMethod');
        $this->assertGraphWithNode();
    }

    private function givenABuilder()
    {
        $this->builder = new Builder('AnNamespace');
    }

    private function whenIAddAnEntry($depth, $function)
    {
        $this->builder->addEntryRecord(
            new Entry(
                $depth,
                $depth,
                0,
                0,
                $function,
                1,
                '',
                tempnam('', ''),
                1
            )
        );
    }

    private function whenIAddALeave($depth)
    {
        $this->builder->addLeaveRecord(new Leave($depth, $depth, 0, 0));
    }

    private function assertSimpleGraph()
    {
        $graph = new Digraph('class_diagram');
        $graph->node('AnNamespace\AnClass');
        $graph->edge(array('AnNamespace\AnClass', 'AnNamespace\AnotherClass'));
        $graph->node('AnNamespace\AnotherClass');
        $expected = new Report($graph);
        $this->assertEquals($expected, $this->builder->build());
    }

    private function assertGraphWithNode()
    {
        $graph = new Digraph('class_diagram');
        $graph->node('AnNamespace\AnClass');
        $this->assertGraph($graph);
    }

    private function assertGraph(Digraph $graph)
    {
        $expected = new Report($graph);
        $this->assertEquals($expected, $this->builder->build());
    }
}
