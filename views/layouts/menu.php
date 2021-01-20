<?php

use app\src\library\AccessChecker;
use dmstr\widgets\Menu;
use yii\helpers\Url;

$isClubVisible = AccessChecker::hasPermission('club.index');
$isPlaceVisible = AccessChecker::hasPermission('place.index');
$isStaffVisible = AccessChecker::hasPermission('staff.index');
$isCustomersVisible = AccessChecker::hasPermission('customer.index');
$isCoachingVisible = AccessChecker::hasPermission('coaching.index');
$isScheduleVisible = AccessChecker::hasPermission('schedule.index');
$isNotificationsVisible = AccessChecker::hasPermission('notification.index');
$isTriggersVisible = AccessChecker::hasPermission('trigger.index');
$isRolesVisible = AccessChecker::hasPermission('role.index');
$isNewsVisible = AccessChecker::hasPermission('news.index');
$isActivityVisible = AccessChecker::hasPermission('activity.index');
$isSaleVisible = AccessChecker::hasPermission('sale.index');
$isTranslationVisible = AccessChecker::hasPermission('translation.index');
$isNewsletterVisible = AccessChecker::hasPermission('newsletter.index');

$currentUrl = Url::current();

$isClubsItemActive = strpos($currentUrl, '/club') !== false;
$isPlaceItemActive = strpos($currentUrl, '/place') !== false;
$isStaffItemActive = strpos($currentUrl, '/staff') !== false;
$isCustomersItemActive = strpos($currentUrl, '/customer') !== false;
$isCoachingItemActive = strpos($currentUrl, '/coaching') !== false;
$isScheduleItemActive = strpos($currentUrl, '/schedule') !== false;
$isNotificationsActive = strpos($currentUrl, '/notification') !== false;
$isTriggersItemActive = strpos($currentUrl, '/trigger') !== false;
$isRolesItemActive = strpos($currentUrl, '/role') !== false;
$isNewsItemActive = strpos($currentUrl, '/news/') !== false;
$isActivityItemActive = strpos($currentUrl, '/activity') !== false;
$isSaleItemActive = strpos($currentUrl, '/sale') !== false;
$isTranslationItemActive = strpos($currentUrl, '/translation') !== false;
$isNewsletterItemActive = strpos($currentUrl, '/newsletter') !== false;

//Menu::$iconClassPrefix = 'fa fa-';
?>

<?= Menu::widget(
    [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
        'items' => [
            ['label' => Yii::t('app', 'Clubs'), 'active' => $isClubsItemActive, 'icon' => 'building', 'url' => ['/club'], 'visible' => $isClubVisible],
            ['label' => Yii::t('app', 'Places'), 'active' => $isPlaceItemActive, 'icon' => 'th-large','url' => ['/place'], 'visible' => $isPlaceVisible],
            ['label' => Yii::t('app', 'News'), 'active' => $isNewsItemActive, 'icon' => 'newspaper-o','url' => ['/news'], 'visible' => $isNewsVisible],
            ['label' => Yii::t('app', 'Sale'), 'active' => $isSaleItemActive, 'icon' => 'dollar','url' => ['/sale'], 'visible' => $isSaleVisible],
            ['label' => Yii::t('app', 'Activity'), 'active' => $isActivityItemActive, 'icon' => 'child','url' => ['/activity'], 'visible' => $isActivityVisible],
            ['label' => Yii::t('app', 'Staff'), 'active' => $isStaffItemActive, 'icon' => 'user','url' => ['/staff'], 'visible' => $isStaffVisible],
            ['label' => Yii::t('app', 'Customers'), 'active' => $isCustomersItemActive, 'icon' => 'users','url' => ['/customer'], 'visible' => $isCustomersVisible],
            ['label' => Yii::t('app', 'Coaching'), 'active' => $isCoachingItemActive, 'icon' => 'clipboard','url' => ['/coaching'], 'visible' => $isCoachingVisible],
            ['label' => Yii::t('app', 'Schedule'), 'active' => $isScheduleItemActive, 'icon' => 'calendar','url' => ['/schedule'], 'visible' => $isScheduleVisible],
            ['label' => Yii::t('app', 'Notifications'), 'active' => $isNotificationsActive, 'icon' => 'envelope-o','url' => ['/notification'], 'visible' => $isScheduleVisible],
            ['label' => Yii::t('app', 'Triggers'), 'active' => $isTriggersItemActive, 'icon' => 'cogs','url' => ['/trigger'], 'visible' => $isTriggersVisible],
            ['label' => Yii::t('app', 'Roles'), 'active' => $isRolesItemActive, 'icon' => 'key','url' => ['/role'], 'visible' => $isRolesVisible],
            ['label' => Yii::t('app', 'Translation'), 'active' => $isTranslationItemActive, 'icon' => 'language','url' => ['/translation'], 'visible' => $isTranslationVisible],
            ['label' => Yii::t('app', 'Newsletter'), 'active' => $isNewsletterItemActive, 'icon' => 'legal','url' => ['/newsletter'], 'visible' => $isNewsletterVisible],
        ],
    ]
) ?>