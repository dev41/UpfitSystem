<?php
namespace app\src\entities\notification;

use app\src\entities\AbstractModel;

abstract class Notification extends AbstractModel
{
    const STATUS_CREATED_LABEL = 'created';
    const STATUS_SENT_LABEL = 'sent';
    const STATUS_DELIVERED_LABEL = 'delivered';
    const STATUS_ALREADY_READ_LABEL = 'already read';

    const STATUS_CREATED = 0;
    const STATUS_SENT = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_ALREADY_READ = 3;

    const STATUS_LABELS = [
        self::STATUS_CREATED => self::STATUS_CREATED_LABEL,
        self::STATUS_SENT => self::STATUS_SENT_LABEL,
        self::STATUS_DELIVERED => self::STATUS_DELIVERED_LABEL,
        self::STATUS_ALREADY_READ => self::STATUS_ALREADY_READ_LABEL,
    ];
}
