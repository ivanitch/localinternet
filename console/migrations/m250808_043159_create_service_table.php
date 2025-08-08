<?php

use yii\db\Migration;

class m250808_043159_create_service_table extends Migration
{
    private const string TABLE_NAME = '{{%service}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull()->unique()->comment('Название услуги'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnTable(self::TABLE_NAME, 'Услуги');
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
