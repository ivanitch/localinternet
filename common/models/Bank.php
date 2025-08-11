<?php

declare(strict_types=1);

namespace common\models;

use common\models\query\BankQuery;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
 *
 * @property array $cities_ids
 * @property array $services_ids
 */
class Bank extends BaseActiveRecordModel
{
    const int STATUS_DELETED = 0;
    const int STATUS_ACTIVE = 1;

    public array $cities_ids = [];
    public array $services_ids = [];

    public static function tableName(): string
    {
        return '{{%bank}}';
    }

    public function rules(): array
    {
        return [
            [['created_at', 'updated_at', 'cities_ids', 'services_ids'], 'safe'],
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

    public static function getStatusesList(): array
    {
        return [
            self::STATUS_ACTIVE  => 'Активный',
            self::STATUS_DELETED => 'Удалён',
        ];
    }

    public static function statusLabel(int $status): string
    {
        $class = match ($status) {
            self::STATUS_DELETED => 'badge bg-secondary',
            default => 'badge bg-success',
        };

        return Html::tag('span', ArrayHelper::getValue(self::getStatusesList(), $status), [
            'class' => $class,
        ]);
    }

    /**
     * @return BankQuery
     */
    public static function find(): BankQuery
    {
        return new BankQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['status']);
        $fields['cities']   = 'cities';
        $fields['services'] = 'services';

        return $fields;
    }
}
