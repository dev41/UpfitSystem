<?php
namespace app\commands;

use app\src\entities\notification\EmailNotification;
use app\src\entities\notification\Notification;
use app\src\entities\user\User;
use app\src\library\BaseCommands;

class NotificationController extends BaseCommands
{
    public function actionSentEmailNotifications()
    {
        $sent = 0;
        $notSent = 0;

        $notifications = EmailNotification::findAll([
            'status' => Notification::STATUS_CREATED,
        ]);

        if (!$notifications) {
            $this->consoleLog('notifications were not found!');
            return;
        }

        $this->consoleLog('are being processed - '. count($notifications) . ' notifications...');
        foreach ($notifications as $key =>  $notification) {
            $this->consoleLog('is being processed - '. $key . ' notifications...');

            $user = User::findOne(['id' => $notification->user_id]);

            if (!$user) {
                $this->consoleLog('user was not found!');
            }

            if ($user->email) {
                \Yii::$app->mailer->compose()
                    ->setFrom(['mailer@gmail.com' => $notification->sender_email])
                    ->setTo($user->email)
                    ->setSubject('subject')
                    ->setTextBody($notification->message)
                    ->send();

                $notification->status = Notification::STATUS_SENT;
                $notification->save(false);
                $sent ++;
            } else {
                $this->consoleLog('user have`t email!');
                $notSent++;
            }
        }

        $this->consoleLog('was updated : ' . count($notifications) . ' notifications.');
        $this->consoleLog('was send : ' . $sent . ' notifications.');
        $this->consoleLog('was`t send: ' . $notSent . ' notifications.');
    }
}