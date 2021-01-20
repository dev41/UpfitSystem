<?php

use app\src\library\AccessChecker;
use yii\helpers\Html;
?>

<script type="text/template" class="js-template-event-view">
    <form>
        <div class="form-group">
            <h3>{title}</h3>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Clubs') ?></strong>
            <p class="js-data">{clubs}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Places') ?></strong>
            <p class="js-data">{places}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Trainers') ?></strong>
            <p class="js-data">{trainers}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Start') ?></strong>
            <p class="js-data">{start}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'End') ?></strong>
            <p class="js-data">{end}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Level') ?></strong>
            <span class="js-data">{level}</span>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Description') ?></strong>
            <p class="js-data">{description}</p>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Capacity') ?>: </strong>
            <span class="js-data">{capacity}</span>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Customers') ?>: </strong>
            <span class="js-data">{customers}</span>
        </div>

        <div class="form-group">
            <strong><?= Yii::t('app', 'Color') ?>: </strong>
            <span class="js-data" style="color: {color}; font-weight: bold;">{color}</span>
        </div>

        <?php if (AccessChecker::hasPermission('schedule.delete')) {
            echo Html::button(
                \Yii::t('app', 'Delete'), [
                'class' => 'js-button-delete btn btn-danger float-right',
                'data-title' => Yii::t('app', 'Event Delete'),
                'data-message' => Yii::t('app', 'Do you really want to delete'),
                ]);
        } ?>

        <?php if (AccessChecker::hasPermission('schedule.update')) {
            echo Html::button(
                \Yii::t('app', 'Update'), [
                'class' => 'js-button-update btn btn-warning float-left mr-3']);
        } ?>

    </form>
</script>