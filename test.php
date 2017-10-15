<?php

require(__DIR__ . '/lib/autoload.php');
require(__DIR__ . '/lib/debug.php');

global $argv;
$app = new ProgressionTester\Application();
try {
    $app->addProgressionAll();
    $app->log('Arithmetic Progression');
    //success
    $args = '1,2,3,4,5';
    $app->parseProgression($args);
    $app->testAll();
    $results = $app->getResult();
    foreach ($results as $progName => $result) {
        if ($progName != 'ArithmeticProgression')
            continue;
        if ($result['result'] === true) {
            $app->log("[success] test string = $args");
        } else {
            $app->log("[fail] test string = $args");
        }
    }
    //fail
    $args = '1,2,55,4,5';
    $app->parseProgression($args);
    $app->testAll();
    $results = $app->getResult();
    foreach ($results as $progName => $result) {
        if ($progName != 'ArithmeticProgression')
            continue;
        if ($result['result'] === false) {
            $app->log("[success] test string = $args");
        } else {
            $app->log("[fail] test string = $args");
        }
    }
} catch (\Exception $e) {
    $app->log('Error: ' . $e->getMessage());
}
