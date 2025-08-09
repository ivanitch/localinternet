<?php

declare(strict_types=1);

namespace common\models\search;

use common\models\Bank;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BankSearch represents the model behind the search form of `common\models\Bank`.
 */
class BankSearch extends Bank
{
    public ?string $country_id = null;
    public ?string $city_id = null;
    public ?string $service_id = null;

    public function rules(): array
    {
        return [
            [['name', 'description', 'country_id', 'city_id', 'service_id'], 'safe'],
            [['id', 'status'], 'integer'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search(array $params, string $formName = null): ActiveDataProvider
    {
        $query = Bank::find();

        $this->load($params, $formName);

        $query->joinWith(['cities' => function ($query) {
            $query->joinWith(['country']);
        }])->joinWith(['services']);

        $query->groupBy(['bank.id']);

        if (!$this->validate()) {
            $query->where('0=1');
        }

        // Добавляем условия фильтрации
        $query->andFilterWhere([
            'bank.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'bank.name', $this->name])
            ->andFilterWhere(['like', 'bank.description', $this->description])
            ->andFilterWhere(['country.id' => $this->country_id])
            ->andFilterWhere(['city.id' => $this->city_id])
            ->andFilterWhere(['service.id' => $this->service_id]);

        $countQuery = clone $query;
        $totalCount = $countQuery->count();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'totalCount' => $totalCount,
            'sort'       => [
                'attributes' => [
                    'name',
                    'description',
                    'status',
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

        $query->select(['bank.*']);

        return $dataProvider;
    }
}
