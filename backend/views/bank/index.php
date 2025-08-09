<?php

use common\models\Bank;
use common\models\City;
use common\models\Country;
use common\models\Service;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var common\models\search\BankSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var Country[] $countries
 * @var City[] $cities
 * @var Service[] $services
 */

$this->title                   = 'Банки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить банк', ['create'], ['class' => 'btn btn-outline-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return Bank::statusLabel($model->status);
                },
                'filter'    => Bank::getStatusesList(),
                'format'    => 'raw',
            ],
            [
                'attribute'          => 'country_id',
                'label'              => 'Страна',
                'value'              => function ($model) {
                    $countries = ArrayHelper::getColumn($model->cities, 'country.name');
                    return implode(', ', array_unique($countries));
                },
                'filter'             => $countries,
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id'    => 'bank-search-country_id',
                ],
            ],
            [
                'attribute'          => 'city_id',
                'label'              => 'Города',
                'value'              => function ($model) {
                    return implode(', ', ArrayHelper::getColumn($model->cities, 'name'));
                },
                'filter'             => $cities,
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'id'    => 'bank-search-city_id',
                ],
            ],
            [
                'attribute'          => 'service_id',
                'label'              => 'Услуги',
                'value'              => function ($model) {
                    return implode(', ', ArrayHelper::getColumn($model->services, 'name'));
                },
                'filter'             => $services,
                'filterInputOptions' => ['class' => 'form-control'],
            ],
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
<?php
$this->registerJs(<<<JS
    $(document).on('change', '#bank-search-country_id', function() {
        var countryId = $(this).val();
        var cityDropdown = $('#bank-search-city_id');
        
        cityDropdown.html('');
        
        if (countryId) {
            $.ajax({
                url: '/bank/get-cities-by-country',
                type: 'GET',
                data: { id: countryId },
                success: function(data) {
                    $.each(data, function(index, city) {
                        cityDropdown.append($('<option>', {
                            value: city.id,
                            text: city.name
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка загрузки городов: ' + error);
                }
            });
        }
    });    
    $('#bank-search-country_id').trigger('change');
JS
);
?>

