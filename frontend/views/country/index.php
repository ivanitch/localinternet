<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this yii\web\View */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Страны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns'      => [
            'name',
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
