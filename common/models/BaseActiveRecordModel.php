<?php

declare(strict_types=1);

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseActiveRecordModel extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => time(),
            ],
        ];
    }

    public function fields(): array
    {
        $fields = parent::fields();
        unset($fields['updated_at'], $fields['created_at']);
        return $fields;
    }
}
