<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\Bank;
use common\models\City;
use common\models\Country;
use common\models\search\BankSearch;
use common\models\Service;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BankController implements the CRUD actions for Bank model.
 */
class BankController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Bank models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel  = new BankSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $countries = ArrayHelper::map(Country::find()->all(), 'id', 'name');

        $citiesQuery = City::find();
        if ($searchModel->country_id) {
            $citiesQuery->where(['country_id' => $searchModel->country_id]);
        }
        $cities = ArrayHelper::map($citiesQuery->all(), 'id', 'name');

        $services = ArrayHelper::map(Service::find()->all(), 'id', 'name');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'countries'    => $countries,
            'cities'       => $cities,
            'services'     => $services,
        ]);
    }

    /**
     * Displays a single Bank model.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Bank();

        $countriesWithCities = Country::find()
            ->joinWith('cities')
            ->all();

        $cities = [];

        foreach ($countriesWithCities as $country) {
            $cities[$country->name] = ArrayHelper::map($country->cities, 'id', 'name');
        }

        $services = ArrayHelper::map(Service::find()->all(), 'id', 'name');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $this->saveRelations($model);
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model'    => $model,
            'cities'   => $cities,
            'services' => $services,
        ]);
    }

    /**
     * Updates an existing Bank model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);

        $countriesWithCities = Country::find()
            ->joinWith('cities')
            ->all();

        $cities = [];

        foreach ($countriesWithCities as $country) {
            $cities[$country->name] = ArrayHelper::map($country->cities, 'id', 'name');
        }

        $services = ArrayHelper::map(Service::find()->all(), 'id', 'name');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $this->saveRelations($model);
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        $model->cities_ids   = ArrayHelper::getColumn($model->cities, 'id');
        $model->services_ids = ArrayHelper::getColumn($model->services, 'id');

        return $this->render('update', [
            'model'    => $model,
            'cities'   => $cities,
            'services' => $services,
        ]);
    }

    /**
     * "Мягкое" удаление банка
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $model         = $this->findModel($id);
        $model->status = Bank::STATUS_DELETED;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Возврат Банка в статус "Активный"
     *
     * @param int $id
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionRestore(int $id): Response
    {
        $model         = $this->findModel($id);
        $model->status = Bank::STATUS_ACTIVE;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Возвращает список городов в формате для выбранной страны
     *
     * @param int $id
     * @return Response
     */
    public function actionGetCitiesByCountry(int $id): Response
    {
        $cities = City::find()
            ->where(['country_id' => $id])
            ->all();

        $response = [];
        foreach ($cities as $city) {
            $response[] = ['id' => $city->id, 'name' => $city->name];
        }

        return $this->asJson($response);
    }

    /**
     * Finds the Bank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return Bank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Bank
    {
        if (($model = Bank::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Сохранение связей
     * Банк <=> Город
     * Банк <=> Услуги
     *
     * @param Bank $model
     * @return void
     */
    protected function saveRelations(Bank $model): void
    {
        $model->unlinkAll('cities', true);
        $model->unlinkAll('services', true);

        if (!empty($model->cities_ids)) {
            foreach ($model->cities_ids as $city_id) {
                $city = City::findOne($city_id);
                if ($city) {
                    $model->link('cities', $city);
                }
            }
        }

        if (!empty($model->services_ids)) {
            foreach ($model->services_ids as $service_id) {
                $service = Service::findOne($service_id);
                if ($service) {
                    $model->link('services', $service);
                }
            }
        }
    }
}
