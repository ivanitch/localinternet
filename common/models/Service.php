<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string $name Название услуги
 * @property int $created_at
 * @property int $updated_at
 * @property Bank[] $banks
 */
class Service extends BaseActiveRecordModel
{
    public static function tableName(): string
    {
        return '{{%service}}';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'name'       => 'Название услуги',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getBanks(): ActiveQuery
    {
        return $this->hasMany(Bank::class, ['id' => 'bank_id'])->viaTable('{{%bank_service}}', ['service_id' => 'id']);
    }
}
