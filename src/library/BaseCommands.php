<?php
namespace app\src\library;

use yii\helpers\Console;
use yii\console\Controller;

/**
 * Class BaseCommands
 */
abstract class BaseCommands extends Controller
{
    public function consoleLog(string $message)
    {
        Console::stdout('    > ' . $message . PHP_EOL);
    }
}