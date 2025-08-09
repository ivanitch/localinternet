<?php

use common\models\Bank;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Банки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
            [
                'attribute' => 'country_id',
                'label'     => 'Страна',
                'value'     => fn(Bank $model) => implode(', ', array_unique(
                    ArrayHelper::getColumn($model->cities, 'country.name')
                )),
            ],
            [
                'attribute' => 'city_id',
                'label'     => 'Города',
                'value'     => fn(Bank $model) => implode(', ', ArrayHelper::getColumn($model->cities, 'name')),
            ],
            [
                'attribute' => 'service_id',
                'label'     => 'Услуги',
                'value'     => fn(Bank $model) => implode(', ', ArrayHelper::getColumn($model->services, 'name')),
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
