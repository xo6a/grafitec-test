<?php

require(__DIR__ . '/lib/autoload.php');
require(__DIR__ . '/lib/debug.php');

global $argv;
$app = new ProgressionTester\Application();
$app->addProgressionAll();
try {
    $app->log('Arithmetic Progression');
    $progName = 'ArithmeticProgression';
    $app->dropResult();
    //success
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,55,4,5';
    runTest($app, $args, $progName, false);

    $app->log('Geometric Progression');
    $progName = 'GeometricProgression';
    $app->dropResult();
    //success
    $args = '1,2,4,8,16';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, false);

    $app->log('Harmonic Progression');
    $progName = 'HarmonicProgression';
    $app->dropResult();
    //success
    $args = '1/2,1/4,1/6,1/8';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/5,1/6,1/8';
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
    $expected = ($expectedResult) ? 'true' : 'false';
    if ($result['result'] === $expectedResult) {
        $app->log("[success] test string = $args | waiting for " . $expected);
    } else {
        $app->log("[fail] test string = $args | waiting for " . $expected);
    }
}