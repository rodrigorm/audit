<?php

namespace RodrigoRM\Audit\PHPUnit;

use PHPUnit_Util_Printer;
use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_AssertionFailedError;
use Exception;
use PHPUnit_Framework_TestSuite;

use RodrigoRM\Audit\Reader;
use RodrigoRM\Audit\ClassDiagram\Builder as ClassDiagramBuilder;

class TestListener extends PHPUnit_Util_Printer implements PHPUnit_Framework_TestListener
{
    private $namespace;
    private $traceFile;

    public function __construct($namespace, $traceFile = null)
    {
        if (is_null($traceFile)) {
            $temp = tempnam('', '');
            $traceFile = $temp . '.xt';
        }

        $this->namespace = $namespace;
        $this->traceFile = $traceFile;
        ini_set('xdebug.collect_params', 1);
        ini_set('xdebug.collect_return', 1);
        ini_set('xdebug.collect_includes', 1);
        xdebug_start_trace(substr($this->traceFile, 0, -3), XDEBUG_TRACE_COMPUTERIZED);
    }

    public function flush()
    {
        xdebug_stop_trace();

        echo "\n\n", 'Generating tracing reports from file ', $this->traceFile, ':', "\n";

        echo '  Class Diagram ...';
        $builder = $this->parse();
        $builder->build()->write('build/logs/class_diagram.dot');
        echo ' done', "\n";
    }

    private function parse()
    {
        $builder = new ClassDiagramBuilder($this->namespace);
        $reader = new Reader($builder);
        $reader->read($this->traceFile);
        return $builder;
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    // @codingStandardsIgnoreStart
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function startTest(PHPUnit_Framework_Test $test) {}

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function endTest(PHPUnit_Framework_Test $test, $time) {}
    // @codingStandardsIgnoreEnd
}
