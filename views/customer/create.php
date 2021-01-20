<?php

use app\src\entities\customer\Customer;
use yii\web\View;

/* @var View $this */
/* @var Customer $customer */

$this->title = $customer->isNewRecord ? \Yii::t('app', 'Customer Create') : \Yii::t('app', 'Customer Update');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('app', 'Customer'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="box uf-form-create">

                <?php if ($this->title): ?>
                    <div class="box-header">
                        <h3 class="box-title"><?= $this->title ?></h3>
                    </div>
                <?php endif; ?>

                <div class="box-body">
                    <?= $this->render('/customer/partial/customer_form', $_params_); ?>
                </div>
            </div>
        </div>
    </div>
</div>