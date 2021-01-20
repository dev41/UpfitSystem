<?php
namespace app\src\dataProviders;

use app\src\entities\coaching\Coaching;
use app\src\entities\coaching\CoachingLevel;
use app\src\entities\place\Club;
use app\src\entities\place\Place;
use app\src\entities\user\User;
use app\src\library\AccessChecker;
use yii\helpers\ArrayHelper;

class CoachingFormDataProvider implements IDataProvider
{
    /** @var Coaching */
    public $coaching;
    /** @var array */
    public $data;

    public function __construct(Coaching $coaching, array $data = [])
    {
        $this->coaching = $coaching;
        $parentCoaching = Coaching::findOne(['id' => $coaching->parent_id]);

        if ($parentCoaching) {
            foreach ($parentCoaching->savingFields() as $field) {
                if ($coaching[$field] === null) {
                    $coaching[$field] = $parentCoaching[$field];
                }
            }
        }

        $this->data = $data + ['coaching' => $coaching];
    }

    public function getData()
    {
        if (empty($this->data['levels'])) {
            $this->data['levels'] = CoachingLevel::find()->all();
        }

        if (empty($this->data['clubs'])) {
            $this->data['clubs'] = AccessChecker::isSuperAdmin() ?
                Club::getAll() :
                Club::getClubsByUserId();
        }

        if (empty($this->data['places'])) {
            $clubIds = [];
            if (!empty($this->coaching->clubs)) {
                $clubIds = ArrayHelper::map($this->coaching->clubs, 'id', 'id');
            }

            if (empty($this->data['trainers'])) {
                $this->data['trainers'] = User::getTrainers($clubIds);
            }
            $this->data['places'] = Place::getAllByClubId($clubIds);
        }

        return $this->data;
    }

}