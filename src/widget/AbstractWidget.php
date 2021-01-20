<?php
namespace app\src\widget;

use yii\base\Widget;

/**
 * Class AbstractWidget
 */
abstract class AbstractWidget extends Widget
{
    /** @var string */
    protected $widgetHash;

    public function init()
    {
        parent::init();
        $this->widgetHash = $this->getWidgetHash();
    }

    public function getWidgetHash(): string
    {
        $hashObject = new \StdClass();
        return spl_object_hash($hashObject);
    }

    public function getUniqueSlug(... $parts): string
    {
        return implode('-', $parts) . '-' . $this->widgetHash;
    }
}