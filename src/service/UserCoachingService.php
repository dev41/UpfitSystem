<?php
namespace app\src\service;

use app\src\entities\user\UserCoaching;

/**
 * Class UserCoachingService
 */
class UserCoachingService extends AbstractService
{
    public function copyUsers(int $coachingFromId, int $coachingToId)
    {
        $coaching = UserCoaching::findAll([
            'coaching_id' => $coachingFromId,
        ]);

        if (!$coaching) {
            return;
        }

        $newCoaching = [];
        foreach ($coaching as $sourceCoaching) {
            $copyCoaching = new UserCoaching();
            $copyCoaching->setAttributes($sourceCoaching->getAttributes());
            $copyCoaching->coaching_id = $coachingToId;

            $newCoaching[] = $copyCoaching;
        }

        UserCoaching::batchInsertByModels($newCoaching);
    }
}