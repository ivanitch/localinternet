<?php

namespace frontend\controllers;

use common\models\City;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class CityController extends Controller
{
    public function actionIndex(): string
    {
        $query = City::find()
            ->select([
                'city.id',
                'city.name',
                'city.country_id',
                'country.name AS country_name'
            ])
            ->joinWith('country');

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ],
                'attributes'   => [
                    'name'       => [
                        'asc'  => ['city.name' => SORT_ASC],
                        'desc' => ['city.name' => SORT_DESC],
                    ],
                    'country_id' => [
                        'asc'   => ['country.name' => SORT_ASC],
                        'desc'  => ['country.name' => SORT_DESC],
                        'label' => 'Страна',
                    ],
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}