<?php

namespace console\controllers;

use common\models\Bank;
use common\models\City;
use common\models\Country;
use common\models\Service;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseConsole;

class FakerController extends Controller
{
    public function actionGenerate(
        int $countriesCount = 30,
        int $citiesCount = 150,
        int $banksCount = 100,
        int $servicesCount = 50
    ): void
    {
        $faker = Factory::create('ru_RU');

        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $countryIds = $this->generateCountries($faker, $countriesCount);
            $cityIds    = $this->generateCities($faker, $countryIds, $citiesCount);
            $serviceIds = $this->generateServices($faker, $servicesCount);
            $this->generateBanks($faker, $banksCount, $cityIds, $serviceIds);

            $transaction->commit();
            $this->stdout("Генерация данных завершена.\n", BaseConsole::FG_GREEN);
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->stderr("Ошибка: {$e->getMessage()} \n", BaseConsole::FG_RED);
        }
    }

    private function withTimestamps(array $rows): array
    {
        $time = time();
        return array_map(fn($row) => array_merge($row, [$time, $time]), $rows);
    }

    /**
     * @param Generator $faker
     * @param int $count
     * @return array
     * @throws Exception
     */
    private function generateCountries(Generator $faker, int $count): array
    {
        $this->stdout("Генерация стран... ");

        $countryGenerator = $faker->unique();

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                $countryGenerator->country
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                Country::tableName(),
                ['name', 'created_at', 'updated_at'],
                $this->withTimestamps($data)
            )
            ->execute();

        $ids = Country::find()->select('id')->column();

        $this->stdout("успешно.\n", BaseConsole::FG_GREEN);
        return $ids;
    }

    /**
     * @param Generator $faker
     * @param array $countryIds
     * @param int $count
     * @return array
     * @throws Exception
     */
    private function generateCities(Generator $faker, array $countryIds, int $count): array
    {
        if (empty($countryIds)) {
            throw new Exception('Нет стран для генерации городов.');
        }

        $this->stdout("Генерация городов... ");

        $fakerLocales = [
            'ru_RU' => Factory::create('ru_RU')->unique(),
            'en_US' => Factory::create('en_US')->unique(),
            'fr_FR' => Factory::create('fr_FR')->unique(),
            'de_DE' => Factory::create('de_DE')->unique(),
            'ar_SA' => Factory::create('ar_SA')->unique(),
        ];

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $randomLocale = $faker->randomElement(array_keys($fakerLocales));
            $cityFaker    = $fakerLocales[$randomLocale];

            $data[] = [
                $cityFaker->city,
                $faker->randomElement($countryIds)
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                City::tableName(),
                ['name', 'country_id', 'created_at', 'updated_at'],
                $this->withTimestamps($data)
            )
            ->execute();

        $ids = City::find()->select('id')->column();

        $this->stdout("успешно.\n", BaseConsole::FG_GREEN);
        return $ids;
    }

    /**
     * @param Generator $faker
     * @param int $count
     * @return array
     * @throws Exception
     */
    private function generateServices(Generator $faker, int $count): array
    {
        $this->stdout("Генерация услуг... ");

        $serviceGenerator = $faker->unique();

        $data = [];
        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                $serviceGenerator->word
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                Service::tableName(),
                ['name', 'created_at', 'updated_at'],
                $this->withTimestamps($data)
            )
            ->execute();

        $ids = Service::find()->select('id')->column();

        $this->stdout("успешно.\n", BaseConsole::FG_GREEN);
        return $ids;
    }

    /**
     * @param Generator $faker
     * @param int $count
     * @param array $cityIds
     * @param array $serviceIds
     * @throws Exception
     */
    private function generateBanks(Generator $faker, int $count, array $cityIds, array $serviceIds): void
    {
        if (empty($cityIds) || empty($serviceIds)) {
            throw new Exception('Нет городов или услуг для генерации банков.');
        }

        $this->stdout("Генерация банков и связей... ");

        $bankGenerator = $faker->unique();
        $banksData     = [];
        for ($i = 0; $i < $count; $i++) {
            $banksData[] = [
                $bankGenerator->company,
                $faker->text(100),
                $faker->numberBetween(0, 1)
            ];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                Bank::tableName(),
                ['name', 'description', 'status', 'created_at', 'updated_at'],
                $this->withTimestamps($banksData)
            )
            ->execute();

        $bankIds = Bank::find()->select('id')->orderBy(['id' => SORT_DESC])->limit($count)->column();
        $bankIds = array_reverse($bankIds);

        $bankCityLinks    = [];
        $bankServiceLinks = [];
        foreach ($bankIds as $bankId) {
            $randomCityIds = $faker->randomElements($cityIds, $faker->numberBetween(1, 3));
            foreach ($randomCityIds as $cityId) {
                $bankCityLinks[] = [$bankId, $cityId];
            }

            $randomServiceIds = $faker->randomElements($serviceIds, $faker->numberBetween(1, 5));
            foreach ($randomServiceIds as $serviceId) {
                $bankServiceLinks[] = [$bankId, $serviceId];
            }
        }

        Yii::$app->db->createCommand()
            ->batchInsert('{{%bank_city}}', ['bank_id', 'city_id'], $bankCityLinks)
            ->execute();

        Yii::$app->db->createCommand()
            ->batchInsert('{{%bank_service}}', ['bank_id', 'service_id'], $bankServiceLinks)
            ->execute();

        $this->stdout("успешно.\n", BaseConsole::FG_GREEN);
    }
}