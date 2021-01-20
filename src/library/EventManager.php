<?php
namespace app\src\library;

use app\src\entities\access\AccessRole;
use app\src\entities\place\Club;
use app\src\entities\translate\Language;
use app\src\entities\trigger\Trigger;
use app\src\entities\trigger\TriggerI18n;
use app\src\entities\trigger\TriggerPlace;
use app\src\entities\trigger\TriggerType;
use app\src\service\AbstractService;
use app\src\service\EmailNotificationService;
use app\src\service\INotificationService;
use app\src\service\MobileNotificationService;
use app\src\service\WebNotificationService;
use yii\helpers\ArrayHelper;

class EventManager
{

    protected static function buildMessage(Trigger $trigger, $user, array $codes, array $data): array
    {
        $messages['uk'] = $trigger->template;
        $translationRu = TriggerI18n::findOne(['id' => $trigger->id, 'language' => Language::LANGUAGE_RU]);
        $translationEn = TriggerI18n::findOne(['id' => $trigger->id, 'language' => Language::LANGUAGE_EN]);

        foreach ($codes as $code) {

            $codePattern = "/\[" . $code . "[^\]]*\]/";

            $messages['uk'] = EventManager::PregReplaceMessage($codePattern, $user, $data, $messages['uk']);
            if ($translationRu) {
                $messages['ru'] = EventManager::PregReplaceMessage($codePattern, $user, $data, $translationRu->template);
            }
            if ($translationEn) {
                $messages['en'] = EventManager::PregReplaceMessage($codePattern, $user, $data, $translationEn->template);
            }
        }

        return $messages;
    }

    protected static function PregReplaceMessage($codePattern, $user, $data, $message)
    {
        return preg_replace_callback($codePattern, function($matches) use ($user, $data) {
            $parts = explode('|', trim($matches[0], '[]'));

            $code = $parts[0];
            $filters = array_slice($parts, 1);

            $value = '';

            if (strpos($code, 'receiver.') === 0) {
                $code = substr($code, strlen('receiver.'));

                if (isset($user[$code])) {
                    $value = $user[$code];
                }

            } elseif (array_key_exists($code, $data)) {
                $value = $data[$code];
            }

            if (!$value) {
                return '';
            }

            foreach ($filters as $filter) {

                preg_match('/^[\w]*/', $filter, $arguments);
                $filterName = reset($arguments);

                preg_match("/'[^\']*'/", $filter, $arguments);

                if (!empty($arguments)) {
                    $arguments = array_map(function($value) {
                        return trim($value, "'");
                    }, $arguments);
                    $arguments = array_merge([$value], $arguments);
                } else {
                    $arguments = [$value];
                }

                $value = call_user_func_array([NotificationFilters::class, $filterName], $arguments);
            }

            if(is_array($value)) {
                $value = implode(', ', $value);
            }

            return $value;
        }, $message);
    }

    /**
     * @param string $event
     * @param array|callable $data
     * @param array $usersEvent
     * @param array|null $clubIds
     * @throws \Exception
     */
    public static function trigger(string $event, $data = null, array $usersEvent = null, $clubIds = null)
    {
        $clubsIds = $clubIds ?? ArrayHelper::map(Club::getClubsByUserId(), 'id', 'id');

        $triggers = Trigger::find()
            ->where([
                'event' => $event,
            ])->leftJoin(['tc' => TriggerPlace::tableName()], 'tc.trigger_id = trigger.id and tc.type = ' . TriggerPlace::TYPE_PARENT_CLUB)
            ->andWhere(['tc.place_id' => $clubsIds])
            ->andWhere(['trigger.event' => $event])
            ->all();

        if (!$triggers) {
            return;
        }

        if (is_callable($data)) {
            $data = $data();
        }
        $data = array_filter((array) $data);

        /**
         * @var Trigger $trigger
         */
        foreach ($triggers as $trigger) {

            $receivers = [];

            if ($trigger->advanced_filters) {
                $users = Trigger::getUsersByTrigger($trigger);

                if ($usersEvent) {
                    foreach ($users as $user) {
                        foreach ($usersEvent as $userEvent) {
                            $userRole = AccessRole::getRoleById($user['role_id']);
                            if ($userRole['slug'] != AccessRole::ROLE_CLIENT) {
                                $receivers[] = $user;
                                break;
                            }

                            if ($user['id'] === $userEvent->id) {
                                $receivers[] = $user;
                            }
                        }
                    }
                } else {
                    $receivers = $users;
                }
            } else {
                $receivers = $usersEvent;
            }

            foreach ($receivers as $receiver) {

                $codes = Trigger::DEFAULT_CODES;
                if (isset(Trigger::EVENT_CODES[$trigger->event])) {
                    $codes = array_merge($codes, Trigger::EVENT_CODES[$trigger->event]);
                }

                $messages = self::buildMessage($trigger, $receiver, $codes, $data);
                self::sendNotification($trigger, $receiver, $messages);
            }
        }
    }

    /**
     * @param Trigger $trigger
     * @param array $user
     * @param array $messages
     * @throws \Exception
     */
    public static function sendNotification(Trigger $trigger, $user, array $messages)
    {
        $notificationServices = [];

        if (is_string($trigger->types)) {
            $trigger->types = TriggerType::findAll(['trigger_id' => $trigger->id]);
        }

        foreach ($trigger->types as $type) {
            switch ($type->type) {
                case Trigger::TYPE_WEB_NOTIFICATION:
                    $notificationServices[] = AbstractService::getService(WebNotificationService::class);
                    break;
                case Trigger::TYPE_EMAIL_NOTIFICATION:
                    $notificationServices[] = AbstractService::getService(EmailNotificationService::class);
                    break;
                case Trigger::TYPE_MOBILE_NOTIFICATION:
                    $notificationServices[] = AbstractService::getService(MobileNotificationService::class);
                    break;
                default:
                    throw new \Exception('Unknown trigger type.');
            }
        }

        /** @var INotificationService $notificationService */
        foreach ($notificationServices as $notificationService) {
            if ($notificationService->send($trigger, $user, $messages)) {
                break;
            };
        }
    }
}