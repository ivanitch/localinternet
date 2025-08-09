<?php

namespace frontend\controllers;

use common\models\Bank;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class BankController extends Controller
{
    public function actionIndex(): string
    {
        $query = Bank::find()
            ->active()
            ->joinWith(['cities' => function ($query) {
                $query->joinWith(['country']);
            }])
            ->joinWith(['services']);

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
                    'name',
                    'description',
                    'country_id' => [
                        'asc'  => ['country.name' => SORT_ASC],
                        'desc' => ['country.name' => SORT_DESC],
                    ],
                    'city_id'    => [
                        'asc'  => ['city.name' => SORT_ASC],
                        'desc' => ['city.name' => SORT_DESC],
                    ],
                    'service_id' => [
                        'asc'  => ['service.name' => SORT_ASC],
                        'desc' => ['service.name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $query->groupBy(['bank.id']);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}