<?php
namespace app\src;

use yii\db\Migration;
use yii\helpers\Console;

class BaseMigration extends Migration
{
    public $tableName = '';

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->tableName = $this->getTableNameByCalledClass();
    }

    public function consoleLog(string $message)
    {
        Console::stdout('    > ' . $message . PHP_EOL);
    }

    protected function getTableNameByCalledClass(): string
    {
        $calledClass = get_called_class();
        $sections = explode('__', $calledClass);
        return (string) end($sections);
    }

    public function getTableOptions(): string
    {
        return $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
    }

    public function printBacktraceFiles()
    {
        $backtrace = array_reverse(debug_backtrace());
        foreach ($backtrace as $value) {
            if (isset($value['file'])) {
                echo $value['file'] . ':' . $value['line'] . "\n";
            }
        }
    }
}