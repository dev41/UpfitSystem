<?php
namespace app\src\helpers;

use app\src\entities\user\Position;
use app\src\entities\user\User;
use yii\helpers\ArrayHelper;

class UserHelper
{
    public static function getPositionsLabelByStaffId(int $staffId, string $glue = ', ')
    {
        $positions = Position::getPositionsByStaffId($staffId);
        return implode($glue, ArrayHelper::map($positions, 'id', 'name'));
    }

    public static function getLogoUsername()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        if (!$user) {
            return '';
        }

        if ($user->username) {
            return $user->username;
        }

        if ($user->first_name) {
            return $user->first_name . ' ' . $user->last_name;
        }

        if ($user->email) {
            return $user->email;
        }

        return $user->fb_user_id;
    }

    public static function getMemberSinceAt()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        if (!$user) {
            return '';
        }

        $date = date('M. Y', strtotime($user->created_at));
        return \Yii::t('app', 'Member since ') . $date;
    }
}