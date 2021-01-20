<?php
namespace app\assets;

use app\src\library\BaseController;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/main.less',
    ];

    public $js = [
        'js/jquery-ui.min.js',
        'js/moment.min.js',
        'js/fullcalendar/fullcalendar.js',
        'js/fullcalendar/locale-all.js',
    ];

    public function init()
    {
        parent::init();
        $viewParams = \Yii::$app->controller->view->params;
        if (!empty($viewParams[BaseController::JS_ENTRY_PATH_PARAM])) {
            $this->js[] = $viewParams[BaseController::JS_ENTRY_PATH_PARAM];
        }
    }

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
