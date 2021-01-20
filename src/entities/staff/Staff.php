<?php
namespace app\src\entities\staff;

use app\src\entities\user\User;
use app\src\service\StaffPositionPlaceService;

/**
 * Class Staff
 */
class Staff extends User
{
    public $clubId;
    public $positions;

    public function rules()
    {
        $rules = array_merge(parent::rules(), [
            [['positions', 'clubId'], 'required', 'when' => function () {
                return $this->validateBehaviour === self::VALIDATE_CREATE;
            }],
            [['clubId'], 'integer'],
        ]);

        return $this->filterRulesByWhenCondition($rules);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->clubId = (int) $this->clubId;

        if (!$this->clubId) {
            return;
        }

        $staffPositionPlaceService = new StaffPositionPlaceService();

        $positions = array_filter((array) $this->positions);
        $staffPositionPlaceService->setPositionsByUserIdAndPlaceId($this->id, $this->clubId, $positions);
    }
}