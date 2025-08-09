<?php

namespace frontend\controllers;

use common\models\Country;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CountryController extends Controller
{
    public function actionIndex(): string
    {
        $query = Country::find()->select(['id', 'name']);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}