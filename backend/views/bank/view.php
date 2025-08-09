<?php

use common\models\Bank;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Bank $model */

$this->title                   = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'status',
                'value'     => $model::statusLabel($model->status),
                'format'    => 'raw',
            ],
            [
                'label'  => 'Города',
                'value'  => function (Bank $model) {
                    $citiesByCountry = [];
                    foreach ($model->cities as $city) {
                        $countryName                     = $city->country->name;
                        $citiesByCountry[$countryName][] = $city->name;
                    }

                    $output = [];
                    foreach ($citiesByCountry as $countryName => $cityNames) {
                        $output[] = "$countryName: " . Yii::$app->formatter->asNtext(implode(", ", $cityNames));
                    }

                    return Yii::$app->formatter->asNtext(implode("\n", $output));
                },
                'format' => 'raw',
            ],
            [
                'label'  => 'Услуги',
                'value'  => function (Bank $model) {
                    $services = ArrayHelper::getColumn($model->services, 'name');
                    return Yii::$app->formatter->asNtext(implode("\n", $services));
                },
                'format' => 'raw',
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
