<?php

use yii\db\Migration;

class m250808_042325_create_country_table extends Migration
{
    private const string TABLE_NAME = '{{%country}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull()->comment('Название страны'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnTable(self::TABLE_NAME, 'Страны');
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
