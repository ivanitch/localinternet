<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Города';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            [
                'attribute' => 'name',
                'label'     => 'Город',
            ],
            [
                'label'     => 'Страна',
                'attribute' => 'country_id',
                'value'     => 'country.name',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
