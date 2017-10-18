<?php

require(__DIR__ . '/lib/autoload.php');

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
    $args = '5,4,3,2,1';
    runTest($app, $args, $progName, true);
    $args = '1.1,1.2,1.3,1.4,1.5';
    runTest($app, $args, $progName, true);
    $args = '1.5,1.4,1.3,1.2,1.1';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,55,4,5';
    runTest($app, $args, $progName, false);
    $args = '1,2,03,4,5';
    runTest($app, $args, $progName, false);
    $args = '1,2,a,4,5';
    runTest($app, $args, $progName, false);
    $args = '1,2,0,4,5';
    runTest($app, $args, $progName, false);
    $args = '1,2,3,2,1';
    runTest($app, $args, $progName, false);
    $args = '1,2,3a,4,5';
    runTest($app, $args, $progName, false);
    $args = 'a,b,c,d,e';
    runTest($app, $args, $progName, false);

    $app->log('Geometric Progression');
    $progName = 'GeometricProgression';
    $app->dropResult();
    //success
    $args = '1,2,4,8,16';
    runTest($app, $args, $progName, true);
    $args = '2,2.4,2.88,3.456,4.1472';
    runTest($app, $args, $progName, true);
    $args = '4.1472,3.456,2.88,2.4,2';
    runTest($app, $args, $progName, true);
    //fail
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, false);
    $args = '1,2,04,8,16';
    runTest($app, $args, $progName, false);
    $args = '1,2,0,8,16';
    runTest($app, $args, $progName, false);
    $args = 'a,b,c,d,e';
    runTest($app, $args, $progName, false);

    $app->log('Harmonic Progression');
    $progName = 'HarmonicProgression';
    $app->dropResult();
    //success
    $args = '1/2,1/3,1/4,1/5,1/6';
    runTest($app, $args, $progName, true);
    $args = '1/10,1/11,1/12,1/13,1/14';
    runTest($app, $args, $progName, true);
    //fail
    $args = '2/2,2/3,2/4,2/5,2/6';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/3,1/4,1/05,1/6';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/4,1/6,1/8';
    runTest($app, $args, $progName, false);
    $args = '1,2,3,4,5';
    runTest($app, $args, $progName, false);
    $args = '1/3,1/4,1/4,1/5,1/6';
    runTest($app, $args, $progName, false);
    $args = '1/6,1/5,1/4,1/3,1/2';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/3,1/4,a/5,1/6';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/3,1/4,1a/5,1/6';
    runTest($app, $args, $progName, false);
    $args = '1/2,1/3,1/4,11/5,1/6';
    runTest($app, $args, $progName, false);
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
    $expected = ($expectedResult) ? 'true' : 'false';
    if ($result['result'] === $expectedResult) {
        $app->log("[success] test string = $args | waiting for " . $expected);
    } else {
        $app->log("[fail] test string = $args | waiting for " . $expected);
    }
}