<?php

namespace api\controllers\v1;

use api\controllers\BaseRestController;
use common\models\Bank;
use common\models\City;
use common\models\Service;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class BankController extends BaseRestController
{
    public string $modelClass = Bank::class;


    public function actions(): array
    {
        $actions = parent::actions();

        unset(
            $actions['index'],
            $actions['view'],
            $actions['delete'],
            $actions['update']
        );

        return $actions;
    }

    public function actionIndex(): ActiveDataProvider
    {
        $query = Bank::find()
            ->select(['id', 'name', 'description'])
            ->active();

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionView(int $id): Bank
    {
        $bank = Bank::find()
            ->active()
            ->with(['cities.country', 'services'])
            ->where(['bank.id' => $id])
            ->one();

        if (!$bank) {
            throw new NotFoundHttpException('Bank not found.');
        }

        return $bank;
    }

    public function actionDelete(int $id): void
    {
        $bank = Bank::findOne($id);
        if (!$bank) {
            throw new NotFoundHttpException('Bank not found.');
        }

        $bank->status = Bank::STATUS_DELETED;

        if (!$bank->save()) {
            throw new ServerErrorHttpException('Failed to soft delete the bank.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    public function actionUpdate(int $id): Bank
    {
        $bank = Bank::findOne($id);
        if (!$bank) {
            throw new NotFoundHttpException('Bank not found.');
        }

        $params = Yii::$app->getRequest()->getRawBody();
        if (empty($params)) {
            throw new BadRequestHttpException('No data received for update.');
        }

        $data = Json::decode($params);
        if (isset($data['name'])) {
            $bank->name = $data['name'];
        }

        if (isset($data['description'])) {
            $bank->description = $data['description'];
        }

        if (isset($data['city_ids']) && is_array($data['city_ids'])) {
            $bank->unlinkAll('cities', true);
            foreach ($data['city_ids'] as $cityId) {
                $city = City::findOne($cityId);
                if ($city) {
                    $bank->link('cities', $city);
                }
            }
        }

        if (isset($data['service_ids']) && is_array($data['service_ids'])) {
            $bank->unlinkAll('services', true);
            foreach ($data['service_ids'] as $serviceId) {
                $service = Service::findOne($serviceId);
                if ($service) {
                    $bank->link('services', $service);
                }
            }
        }

        if (!$bank->save()) {
            throw new ServerErrorHttpException('Failed to update the bank.');
        }

        return $bank;
    }
}