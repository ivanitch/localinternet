<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name Название города
 * @property int $country_id ID страны (Ссылка на страну)
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Bank[] $banks
 * @property Country $country
 */
class City extends BaseActiveRecordModel
{

    public static function tableName(): string
    {
        return '{{%city}}';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            ['name', 'required', 'message' => 'Название города не может быть пустым.'],
            ['country_id', 'required', 'message' => 'Необходимо выбрать страну.'],
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['country_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Country::class,
                'targetAttribute' => ['country_id' => 'id']
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'name'       => 'Название города',
            'country_id' => 'ID страны (Ссылка на страну)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function countriesList(): array
    {
        return ArrayHelper::map(Country::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function getBanks(): ActiveQuery
    {
        return $this->hasMany(Bank::class, ['id' => 'bank_id'])->viaTable('{{%bank_city}}', ['city_id' => 'id']);
    }

    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['country_id']);
        $fields['country'] = fn(): string => $this->country->name;

        return $fields;
    }
}
