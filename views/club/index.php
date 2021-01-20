<?php

use app\src\entities\place\Club;
use app\src\entities\place\ClubSearch;
use app\src\helpers\ImageHelper;
use app\src\helpers\ViewHelper;
use app\src\library\AccessChecker;
use kartik\datecontrol\DateControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\src\widget\UFGridView;
use yii\helpers\Url;
use yii\web\View;

/* @var View $this */
/* @var ClubSearch $searchModel */
/* @var ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Clubs');
$this->params['breadcrumbs'][] = $this->title;

$hasUpdateClubPermission = AccessChecker::hasPermission('club.update');
$hasDeleteClubPermission = AccessChecker::hasPermission('club.delete');

echo ViewHelper::getJSMap(ViewHelper::JS_MAP_TRANSLATE, [
    'delete_popup.confirm_title' => Yii::t('app', 'Do you really want to delete this club?'),
]);
?>

<div class="box uf-listing js-listing">

    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app', 'Club List') ?></h3>

        <?php if (AccessChecker::hasPermission('club.create')) {
            echo Html::button(Yii::t('app', 'Create Club'), [
                'data-url' => Url::to(['club/get-create-club-form']),
                'data-title' => \Yii::t('app', 'Club Create'),
                'data-notification-message' => [
                    'success' => Yii::t('app', 'Club have been successfully created.'),
                    'error' => Yii::t('app', 'Club have\'t been created.'),
                ],
                'class' => 'js-button-create btn btn-sm btn-success float-right',
            ]);
        } ?>

    </div>

    <div class="box-body">

        <?php \yii\widgets\Pjax::begin(['id' => 'club-index']); ?>

        <?= $this->render('/_templates/confirm-dialog.php') ?>
        <div class="club-index">
            <?= UFGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'name',
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    [
                        'attribute' => 'city',
                        'sortLinkOptions' => ['class' => 'sorting'],
                    ],
                    'address',
                    [
                        'attribute' => 'logo',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (empty($model['logo'])) {
                                return null;
                            }
                            $html = '';
                            $place = new Club(['id' => $model['id']]);
                            $html = $html . '<img class="img-thumbnail" src="' . ImageHelper::getUrl($place, $model['logo'], '/logo') . '"></img>';
                            return Html::a($html);
                        }
                    ],
                    'description',
                    [
                        'attribute' => 'created_at',
                        'filter' => DateControl::widget([
                            'model' => $searchModel,
                            'type' => DateControl::FORMAT_DATE,
                            'attribute' => 'created_at',
                            'widgetOptions' => [
                                'pluginOptions' => [
                                    'format' => 'php:Y-m-d'
                                ]
                            ],
                            'autoWidget' => true,
                        ]),
                        'headerOptions' => ['style' => 'min-width: 220px'],
                        'format' => 'html',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return '';
                            },
                            'update' => function ($url, $model) use ($hasUpdateClubPermission) {
                                if (!$hasUpdateClubPermission) {
                                    return '';
                                }
                                $title = htmlspecialchars(Yii::t('app', 'Update "') . $model['name'] . '"');
                                return Html::a('<span class="glyphicon glyphicon-pencil"
                                    data-title="' . $title . '"></span>', $url);
                            },
                            'delete' => function ($url, $model) use ($hasDeleteClubPermission) {
                                if (!$hasDeleteClubPermission) {
                                    return '';
                                }

                                $title = htmlspecialchars(Yii::t('app', 'Club Delete'));
                                $message = htmlspecialchars(Yii::t('app', 'Do you really want to delete "') . $model['name'] . Yii::t('app', '" club') . '?');
                                return Html::tag('span', '', [
                                    'class' => 'js-delete uf-listing-button uf-listing-button-delete glyphicon glyphicon-trash',
                                    'data-url' => $url,
                                    'data-title' => $title,
                                    'data-confirm-message' => $message,
                                    'data-notification-message' => [
                                        'success' => Yii::t('app', 'Club have been successfully deleted.'),
                                        'error' => Yii::t('app', 'Club have\'t been deleted.'),
                                    ],
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, $model) {
                            switch ($action) {
                                case 'update': return Url::to(['club/update', 'id' => $model['id']]);
                                case 'delete': return Url::to(['club/delete', 'id' => $model['id']]);
                                default: return Url::to(['club/view', 'id' => $model['id']]);
                            }
                        }
                    ],
                ],
            ]); ?>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>

        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    //appId      : '133432240860324',
                    appId: '277210412864977',
                    xfbml: true,
                    version: 'v3.0'
                });
                FB.AppEvents.logPageView();
            };

            (function (d, s, id) {
                return;
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            function fbLogin() {
                FB.getLoginStatus(function (response) {
                    if (response.authResponse) {
                        console.log('Welcome!  Fetching your information.... ');
                        console.log(response);
                    } else {
                        console.log('Юзер был не залогинен в самом ФБ, запускаем окно логинизирования');
                        FB.login(function (response) {
                            if (response.authResponse) {
                                console.log('Welcome!  Fetching your information.... ');
                                console.log(response);
                            } else {
                                console.log('Походу пользователь передумал логиниться через ФБ');
                            }
                        });
                    }
                }, {
                    scope: 'email,id'
                });
            }
        </script>

        <button style="display: none" onclick="fbLogin()">LOGIN FACEBOOK</button>

    </div>
</div>