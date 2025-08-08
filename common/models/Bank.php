<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "bank".
 *
 * @property int $id
 * @property string $name Название банка
 * @property string|null $description Описание банка
 * @property int $status Статус банка (для "мягкого" удаления)
 * @property int $created_at
 * @property int $updated_at
 *
 * @property City[] $cities
 * @property Service[] $services
 */
class Bank extends BaseActiveRecordModel
{

    public static function tableName(): string
    {
        return '{{%bank}}';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['name'], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Название банка',
            'description' => 'Описание банка',
            'status'      => 'Статус банка',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    public function getCities(): ActiveQuery
    {
        return $this->hasMany(City::class, ['id' => 'city_id'])->viaTable('{{%bank_city}}', ['bank_id' => 'id']);
    }

    public function getServices(): ActiveQuery
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])->viaTable('{{%bank_service}}', ['bank_id' => 'id']);
    }
}
