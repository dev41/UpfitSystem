<?php
namespace app\src\helpers;

use app\src\entities\trigger\Trigger;

class TriggerHelper
{
    public static function getTypeTemplates(array $types = null, bool $unsetExisting = false)
    {
        $labels = [];
        if ($types) {
            if ($unsetExisting) {
                $screenedLabels = Trigger::TYPE_LABELS;
                foreach ($types as $type) {
                    unset($screenedLabels[$type->type]);
                }

                foreach ($screenedLabels as $key => $label) {
                    $labels[$key]['content'] = '<div class="grid-item text-danger" id="' . $key . '">' . \Yii::t('app', $label) . '</div>';
                }

            } else {
                foreach ($types as $key => $type) {
                    $labels[$type->type]['content'] = '<div class="grid-item text-danger" id="' . $type->type . '">' . \Yii::t('app', Trigger::TYPE_LABELS[$type->type]) . '</div>';
                }
            }
        } else {
            foreach (Trigger::TYPE_LABELS as $key => $label) {
                if ($key != Trigger::TYPE_MOBILE_NOTIFICATION) {
                    $labels[$key]['content'] = '<div class="grid-item text-danger" id="' . $key . '">' . \Yii::t('app', $label) . '</div>';
                }
            }
        }

        return $labels;
    }

    public static function getTypeLabels($types = null)
    {
        $typesLabels = [];

        if (!$types) {
            return null;
        } elseif (is_array($types)) {
            foreach ($types as $key => $type) {
                $typesLabels[] = Trigger::TYPE_LABELS[$type->type];
            }
        } else {
            $types = explode(', ', $types);

            foreach ($types as $key => $type) {
                $typesLabels[] = Trigger::TYPE_LABELS[$type];
            }
        }

        return implode(', ', $typesLabels);
    }

    public static function isTriggerTypeEmailNotification($types)
    {
        if ($types) {
            foreach ($types as $type) {
                if (Trigger::TYPE_LABELS[$type->type] === Trigger::TYPE_EMAIL_NOTIFICATION_LABEL) {
                    return true;
                }
            }
        }
        return false;
    }
}