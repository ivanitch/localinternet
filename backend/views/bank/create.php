<?php

use common\models\City;
use common\models\Service;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Bank $model */
/** @var City[] $cities */
/** @var Service[] $services */

$this->title                   = 'Create Bank';
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'    => $model,
        'cities'   => $cities,
        'services' => $services,
    ]) ?>

</div>
