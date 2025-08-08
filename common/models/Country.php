<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string $name Название страны
 * @property int $created_at
 * @property int $updated_at
 *
 * @property City[] $cities
 */
class Country extends BaseActiveRecordModel
{
    public static function tableName(): string
    {
        return '{{%country}}';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'required'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'name'       => 'Название страны',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCities(): ActiveQuery
    {
        return $this->hasMany(City::class, ['country_id' => 'id']);
    }
}
