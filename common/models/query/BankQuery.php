<?php

declare(strict_types=1);

namespace common\models\query;

use common\models\Bank;
use yii\db\ActiveQuery;

class BankQuery extends ActiveQuery
{
    public function active(): BankQuery
    {
        return $this->andWhere(['status' => Bank::STATUS_ACTIVE]);
    }
}
