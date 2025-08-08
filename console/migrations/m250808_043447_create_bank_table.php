<?php

use yii\db\Migration;

class m250808_043447_create_bank_table extends Migration
{
    private const string TABLE_NAME = '{{%bank}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull()->comment('Название банка'),
            'description' => $this->text()->comment('Описание банка'),
            'status'      => $this->smallInteger()->notNull()->defaultValue(1)->comment('Статус банка (для "мягкого" удаления)'),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);

        $this->addCommentOnTable(self::TABLE_NAME, 'Банки');
    }

    public function safeDown(): void
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
