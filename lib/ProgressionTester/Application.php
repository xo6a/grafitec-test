<?php

namespace ProgressionTester;

use ProgressionTester\components\AbstractProgression;

class Application
{
    /** @var */
    private $input;
    /** @var */
    private $progressions = [];
    /** @var */
    private $result;
    /** @var */
    private $progressionClassNamespace = '\\ProgressionTester\\progressions\\';

    const INTERFACE_CLI = 'cli'; //код интерфейса командой строки
    const INTERFACE_HTTP = 'http'; //код интерфейса веб
    const INTERFACE_UNDEFINED = 'undefined'; //код неизвестного интерфейса

    /**
     * Запуск приложения
     * @return bool
     */
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

    /**
     * Определить код интерфейса
     * @return string
     */
    public function getPhpInterface()
    {
        switch (php_sapi_name()) {
            case 'cli':
                return self::INTERFACE_CLI;
                break;
        }
        return self::INTERFACE_UNDEFINED;
    }

    /**
     * Запуск приложения из консоли
     */
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

    /**
     * Запуск приложения из веб
     */
    public function runHttp()
    {
        //todo use this for http response
    }

    /**
     * Парсинг ряда потенциальной прогрессии из массива
     * @param $args string строка с рядом элементов, разделенных запятыми, на проверку, является ли она прогрессией
     * @throws \Exception
     */
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

    /**
     * Найти и добавить все прогрессии в лист проверки
     */
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

    /**
     * Получить абсалютный путь к папке с прогрессиями
     * @return string абсалютный путь к папке с прогрессиями
     */
    public function getProgressionDirPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'progressions' . DIRECTORY_SEPARATOR;
    }

    /**
     * Получить имя файла
     * @param $file string абсалютный путь к файлу
     * @return null|string
     */
    public function parseProgressionName($file)
    {
        $strPos = strpos($file, '.php');
        if ($strPos === false) {
            return null;
        }
        return substr($file, 0, $strPos);
    }

    /**
     * Добавить прогрессию в лист проверки
     * @param $progName string навазвание класса прогрессии
     */
    public function addProgression($progName)
    {
        $progression = $this->getProgressionObject($progName);
        $this->progressions[$progName] = $progression;
    }

    /**
     * Добавить объект прогрессии в лист проверки
     * @param $progName string навазвание класса прогрессии
     * @return AbstractProgression объект прогрессии
     * @throws \Exception
     */
    public function getProgressionObject($progName)
    {
        $progClassName = $this->getProgressionClassName($progName);
        $progression = new $progClassName();
        if (!($progression instanceof AbstractProgression)) {
            throw new \Exception(sprintf('Class %s is not progression class', get_class($progression)));
        }
        return $progression;
    }

    /**
     * Получить имя класса прогресии по её имени с неймспейсом
     * @param $progName string навазвание класса прогрессии
     * @return string
     */
    public function getProgressionClassName($progName)
    {
        return $this->progressionClassNamespace . $progName;
    }

    /**
     * Протестировать ряд на прогрессию
     * @param $progName string навазвание класса прогрессии
     */
    public function test($progName)
    {
        $progression = $this->getProgressionObject($progName);
        $this->runTest($progName, $progression);
    }

    /**
     * Протестировать ряд на все прогрессии в листе
     */
    public function testAll()
    {
        foreach ($this->progressions as $progName => $progression) {
            $this->runTest($progName, $progression);
        }
    }

    /**
     * Получить результаты тестирования ряда на прогрессию
     * @param $progName string название класса прогрессии
     * @param $progression AbstractProgression объект прогрессии
     */
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
                'failElement' => $progression->getFailElement(),
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Получить результат
     * @return array [прогрессия => [результаты]]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Сброс результатов
     * @return array
     */
    public function dropResult()
    {
        return $this->result = [];
    }

    /**
     * Вывод данных
     * @param $view
     * @param array $data
     * @throws \Exception
     */
    public function render($view, $data = [])
    {
        $path = __DIR__ . "/views/$view.php";
        if (!file_exists($path))
            throw new \Exception("View file not found with path $path");
        include $path;
    }

    /**
     * Получить результаты
     * @return mixed
     */
    public function prepareResult()
    {
        return $this->getResult();
    }

    /**
     * Функция логирования
     * todo использовать отдельный класс логгера
     * @param $message string сообщение
     * @param $level int код ошибки
     * @param $context array
     */
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