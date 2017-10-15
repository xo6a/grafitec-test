<?php

namespace ProgressionTester;

use ProgressionTester\components\AbstractProgression;

class Application
{
    private $input;
    private $progressions = [];
    private $result;
    private $progressionClassNamespace = '\\ProgressionTester\\progressions\\';

    const INTERFACE_CLI = 'cli';
    const INTERFACE_HTTP = 'http';
    const INTERFACE_UNDEFINED = 'undefined';

    public function run()
    {
        switch ($this->getPhpInterface()) {
            case self::INTERFACE_CLI:
                $this->runCli();
                break;
            case self::INTERFACE_HTTP:
                $this->runHttp();
                break;
        }
        return false;
    }

    public function getPhpInterface()
    {
        switch (php_sapi_name()) {
            case 'cli':
                return self::INTERFACE_CLI;
                break;
        }
        return self::INTERFACE_UNDEFINED;
    }

    public function runCli()
    {
        try {
            global $argv;
            @$args = $argv[1];
            $this->parseProgression($args);
            $this->addProgressionAll();
            $this->testAll();
            $data = $this->prepareResult();
            $this->render('cli', $data);
        } catch (\Exception $e) {
            $this->log('Error: ' . $e->getMessage());
        }
    }

    public function runHttp()
    {
        //todo use this for http response
    }

    public function parseProgression($args)
    {
        if (!$args) {
            $this->render('help');
            throw new \Exception('Not valid args. Args are not set.');
        }
        $args = explode(',', $args);
        if (count($args) < 2) {
            $this->render('help');
            throw new \Exception('Not valid args. Not enough arguments.');
        }
        $this->input = $args;
    }

    public function addProgressionAll()
    {
        $path = $this->getProgressionDirPath();
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..' || is_dir($path . $file)) {
                continue;
            }
            $progName = $this->parseProgressionName($file);
            if ($progName) {
                $this->addProgression($progName);
            }
        }
    }

    public function getProgressionDirPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'progressions' . DIRECTORY_SEPARATOR;
    }

    public function parseProgressionName($file)
    {
        $strPos = strpos($file, '.php');
        if ($strPos === false) {
            return null;
        }
        return substr($file, 0, $strPos);
    }

    public function addProgression($progName)
    {
        $progression = $this->getProgressionObject($progName);
        $this->progressions[$progName] = $progression;
    }

    public function getProgressionObject($progName)
    {
        $progClassName = $this->getProgressionClassName($progName);
        $progression = new $progClassName();
        if (!($progression instanceof AbstractProgression)) {
            throw new \Exception(sprintf('Class %s is not progression class', get_class($progression)));
        }
        return $progression;
    }

    public function getProgressionClassName($progName)
    {
        return $this->progressionClassNamespace . $progName;
    }

    public function test($progName)
    {
        $progression = $this->getProgressionObject($progName);
        $this->runTest($progName, $progression);
    }

    public function testAll()
    {
        foreach ($this->progressions as $progName => $progression) {
            $this->runTest($progName, $progression);
        }
    }

    public function runTest($progName, AbstractProgression $progression)
    {
        try {
            $result = $progression->validate($this->input);
            $this->result[$progName] = [
                'result' => $result,
                'progClassName' => $progName,
                'progFullName' => $progression->getName(),
                'failElement' => $progression->getFailElement(),
            ];
        } catch (\Exception $e) {
            $this->result[$progName] = [
                'result' => false,
                'progClassName' => $progName,
                'progFullName' => $progression->getName(),
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function render($view, $data = [])
    {
        $path = __DIR__ . "/views/$view.php";
        if (!file_exists($path))
            throw new \Exception("View file not found with path $path");
        include $path;
    }

    public function prepareResult()
    {
        return $this->getResult();
    }

    public function log($message, $level = 0, array $context = [])
    {
        //todo use logger class instead function
        if ($this->getPhpInterface() == self::INTERFACE_CLI) {
            if (is_array($message))
                var_dump($message);
            else
                echo $message . PHP_EOL;
        }
    }

}