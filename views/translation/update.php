<?php

use app\src\entities\translate\Translation;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model Translation */

$this->title = Yii::t('app', 'Edit Translation') . ': ' . StringHelper::truncate(strip_tags($model->message->message), 50, '...');
?>
<div class="js-edit-translation-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="box uf-form-create">
                    <div class="translation-update">
                        <?php if ($this->title): ?>
                            <div class="box-header">
                                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            </div>
                        <?php endif; ?>
                        <div class="box-body">
                            <?= $this->render('_form', [
                                'model' => $model,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
