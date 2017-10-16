<?php

require(__DIR__ . '/lib/autoload.php');
require(__DIR__ . '/lib/debug.php');

global $argv;
$app = new ProgressionTester\Application();
$app->addProgressionAll();
try {
    $app->log('Arithmetic Progression');
    $progName = 'ArithmeticProgression';
    $results = $app->dropResult();
    //success
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,55,4,5';
    runTest($app, $args, $progName, false);

    $app->log('Geometric Progression');
    $progName = 'GeometricProgression';
    $results = $app->dropResult();
    //success
    $args = '1,2,4,8,16';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, false);
} catch (\Exception $e) {
    $app->log('Error: ' . $e->getMessage());
}

/**
 * @param $app ProgressionTester\Application
 * @param $args
 * @param $progName
 * @param $expectedResult
 */
function runTest($app, $args, $progName, $expectedResult)
{
    $app->parseProgression($args);
    $app->test($progName);
    $result = $app->getResult();
    $result = $result[$progName];
    if ($result['result'] === $expectedResult) {
        $app->log("[success] test string = $args");
    } else {
        $app->log("[fail] test string = $args");
    }
}