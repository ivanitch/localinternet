<?php

use common\models\City;
use common\models\Service;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Bank $model
 * @var yii\widgets\ActiveForm $form
 *
 * @var City[] $cities
 * @var Service[] $services
 *
 */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cities_ids')->listBox(
        $cities,
        [
            'multiple' => true,
            'size'     => 10,
        ]
    )->label('Города') ?>

    <?= $form->field($model, 'services_ids')->listBox(
        $services,
        [
            'multiple' => true,
            'size'     => 10,
        ]
    )->label('Услуги') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
