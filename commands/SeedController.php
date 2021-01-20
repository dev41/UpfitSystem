<?php
namespace app\commands;

use app\src\entities\TransformTransactions;
use yii\console\Controller;
use yii\console\Exception;
use Yii;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class SeedController extends Controller
{

    public static $types = [
        'seeder' => 1
    ];

    public $nameLimit = 255;

    public $seedPath = '@app/migrations/seeds';

    public $seedNamespace = 'app\\migrations\\seeds\\';


    private function generateClassName($name, $type)
    {
        $name = trim($name, '\\');
        if (strpos($name, '\\') !== false) {
            $name = substr($name, strrpos($name, '\\') + 1);
        }

        $class = array_search($type, self::$types)[0] . gmdate('ymd_His') . '_' . $name;

        return $class;
    }

    public function actionIndex()
    {
        return $this->actionUp("all");
    }

    public function actionUp($limit = 0)
    {
        $limit = (int) $limit;

        if ($limit === 'all') {
            $limit = null;
        }

        $names = [];
        $differences = [];

        $path = Yii::getAlias($this->seedPath);
        if (!is_dir($path)) {
            $this->stdout("No seed has been done before.\n", Console::FG_YELLOW);
            return;
        }

        $files = scandir($path);

        foreach ($files as $file) {
            $basename = basename($file, ".php");
            if ($basename === '.' || $basename === '..' || strtoupper($basename[0]) === 'I') {
                continue;
            } else {
                $names[]['name'] = basename($file, ".php");
            }

        }

        asort($names);

        foreach ($names as $name) {
            if (empty(TransformTransactions::findOne(['name' => $name['name']])))
                $differences[] = $name['name'];
        }

        if ($limit > 0) {
            $differences = array_slice($differences, 0, $limit);
        }

        if (empty($differences)) {
            $this->stdout("No new seeds found. Your system is up-to-date.\n", Console::FG_GREEN);
            return;
        }

        foreach ($differences as $difference) {
            if ($difference === '.' || $difference === '..' || $difference === 'ISeeder') {
                continue;
            }
            $class = $this->seedNamespace . $difference;
            $seeder = new $class();
            $seeder->consoleLog('');
            $seeder->consoleLog($class . ' start...');
            $seeder->seed();
            $seeder->consoleLog($class . ' done...');

            $transformTransaction = new TransformTransactions();
            $transformTransaction->name = $difference;
            $transformTransaction->type = 1;
            $transformTransaction->apply_time = time();
            $transformTransaction->save();
        }
    }

    /**
     * @param $name
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionCreate($name)
    {
        if (!preg_match('/^[\w\\\\]+$/', $name)) {
            throw new Exception('The seeds name should contain letters, digits, underscore and/or backslash characters only.');
        }

        $className = $this->generateClassName($name, self::$types['seeder']);

        // Abort if name is too long
        if ($this->nameLimit !== null && strlen($className) > $this->nameLimit) {
            throw new Exception('The seed name is too long.');
        }

        $path = Yii::getAlias($this->seedPath);

        $file = $path . DIRECTORY_SEPARATOR . $className . '.php';
        if ($this->confirm("Create new seed '$file'?")) {
            FileHelper::createDirectory($path);
            file_put_contents($file, " ", LOCK_EX);
            $this->stdout("New seed created successfully.\n", Console::FG_GREEN);
        }

    }

    public function actionClean($limit = 1)
    {
        $limit = (int) $limit;

        if ($limit === 'all') {
            $limit = null;
        }

        $transformTransactions = TransformTransactions::getFindArrayName(self::$types['seeder'], $limit);

        $path = Yii::getAlias($this->seedPath);

        if (empty($transformTransactions) || !is_dir($path)) {
            $this->stdout("No seed has been done before.\n", Console::FG_YELLOW);
            return;
        }

        foreach ($transformTransactions as $transformTransaction) {

            $class = $this->seedNamespace . $transformTransaction['name'];
            $seeder = new $class();
            $seeder->consoleLog('');
            $seeder->consoleLog($class . ' start...');
            $seeder->clean();
            $seeder->consoleLog($class . ' done...');

            TransformTransactions::deleteAll(['name' => $transformTransaction['name']]);
        }
    }
}