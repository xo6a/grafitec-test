<?php

require(__DIR__ . '/lib/autoload.php');
require(__DIR__ . '/debug.php');

$app = new ProgressionTester\Application();
$app->run();
