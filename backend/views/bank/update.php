<?php

use common\models\City;
use common\models\Service;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Bank $model */
/** @var City[] $cities */
/** @var Service[] $services */

$this->title                   = 'Update Bank: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'    => $model,
        'cities'   => $cities,
        'services' => $services,
    ]) ?>

</div>
