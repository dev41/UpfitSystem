<?php
namespace app\src\entities\coaching;

/**
 * Class CopyCoaching
 * @package app\src\entities\coaching
 *
 * this class resolve kartik conflict with load 2 forms with same the coaching fields
 */
class CopyCoaching extends Coaching
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id'], 'integer'],
        ]);
    }
}