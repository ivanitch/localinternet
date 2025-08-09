<?php

use common\models\Bank;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\BankSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title                   = 'Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bank', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value'     => function (Bank $model) {
                    return Bank::statusLabel($model->status);
                },
                'filter'    => Bank::getStatusesList(),
                'format'    => 'raw',
            ],
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class'          => ActionColumn::class,
                'template'       => '{view} {update} {delete} {restore}',
                'buttons'        => [
                    'view'    => function ($url, Bank $model) {
                        return Html::a(
                            '<i class="bi bi-eye-fill"></i>',
                            $url,
                            [
                                'class' => 'btn btn-sm btn-outline-info',
                                'title' => 'Просмотр',
                            ]
                        );
                    },
                    'update'  => function ($url, Bank $model) {
                        return Html::a(
                            '<i class="bi bi-pencil-fill"></i>',
                            $url,
                            [
                                'class' => 'btn btn-sm btn-outline-warning',
                                'title' => 'Редактировать',
                            ]
                        );
                    },
                    'delete'  => function ($url, Bank $model) {
                        if ($model->status === Bank::STATUS_ACTIVE) {
                            return Html::a('<i class="bi bi-trash-fill"></i>', $url, [
                                'class' => 'btn btn-sm btn-outline-danger',
                                'title' => 'Удалить',
                                'data'  => [
                                    'confirm' => 'Вы уверены, что хотите удалить этот банк?',
                                    'method'  => 'post',
                                ],
                            ]);
                        }
                        return '';
                    },
                    'restore' => function ($url, Bank $model) {
                        if ($model->status === Bank::STATUS_DELETED) {
                            return Html::a('<i class="bi bi-recycle"></i>', ['restore', 'id' => $model->id], [
                                'class' => 'btn btn-sm btn-outline-secondary',
                                'title' => 'Восстановить',
                                'data'  => [
                                    'confirm' => 'Вы уверены, что хотите восстановить этот банк?',
                                    'method'  => 'post',
                                ],
                            ]);
                        }
                        return '';
                    },
                ],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; width: 120px;'
                ],
                'urlCreator'     => function ($action, Bank $model, $key, $index, $column) {
                    if ($action === 'delete' && $model->status === Bank::STATUS_ACTIVE) {
                        return Url::toRoute(['delete', 'id' => $model->id]);
                    }
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
