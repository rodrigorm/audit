<?php /** a Courtesy of Respect/Foundation */

date_default_timezone_set('UTC');

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->addPsr4('TraceAnalyzer\\Test\\', __DIR__ . '/TraceAnalyzer/Test/');
