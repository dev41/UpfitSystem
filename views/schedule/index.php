<?php

use app\src\entities\coaching\Event;
use app\src\entities\place\Place;

/**
 * @var \yii\web\View $this
 * @var Event[] $events
 * @var array $coaching
 * @var Place $place
 * @var int $clubId
 * @var array $clubs
 * @var boolean $renderAjax
 */

$renderAjax = $renderAjax ?? false;
?>

<script type="text/template" class="js-template-header-day">
    <div class="vertical flip-container js-flip-container">
        <div class="flipper">
            <div class="front">{front}</div>
            <div class="back"><button>copy</button></div>
        </div>
    </div>
</script>

<div class="panel-group uf-schedule-container">
    <div class="box">
        <div class="box-body">

            <div class="js-schedule-container position-relative" data-club_id="<?= $clubId ?? '' ?>">
                <div class="container-fluid ">
                    <div class="row">

                        <div class="col-md-2">
                            <?php if (!$renderAjax) {
                                echo $this->render('partial/select-club-form.php', $_params_);
                            } ?>
                            <?= $this->render('partial/coaching-list.php', $_params_) ?>
                        </div>

                        <div class="col-md-7">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class='js-calendar' data-events='<?= json_encode($events); ?>' data-lang=<?= json_encode(Yii::$app->language); ?>></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-body uf-event-container js-event-view-container">
                                    <h3><?= Yii::t('app', 'Event information') ?></h3>
                                </div>
                                <?= $this->render('/_templates/event-view.php') ?>
                            </div>
                        </div>

                    </div>

                    <div class="js-popup-event uf-popup" title="<?= Yii::t('app', 'Add event'); ?>"></div>

                </div>

                <?= $this->render('/_templates/tooltip.php') ?>
                <?= $this->render('/_templates/confirm-dialog.php') ?>
            </div>

        </div>
    </div>
</div>