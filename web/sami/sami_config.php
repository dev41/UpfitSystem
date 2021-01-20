<?php
use Sami\Sami;
use web\sami\upfit\src\Theme;

$rootDir = dirname(dirname(__DIR__));
$baseDir = dirname(__FILE__);
$sourceDir = $rootDir . '/api/docs';

$sami = new Sami($sourceDir, [
    'template_dirs' => [$baseDir],
    'build_dir' => $baseDir . '/build',
    'cache_dir' => $baseDir . '/cache',
    'theme' => 'upfit-theme',
    'title' => 'UpfitSystem API Documentations v1.0',
]);
require_once($baseDir . '/upfit-theme/src/Theme.php');

$sami = Theme::prepare($sami);
return $sami;