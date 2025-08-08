<?php

use yii\db\Migration;

class m250808_042611_create_city_table extends Migration
{
    private const string TABLE_NAME = '{{%city}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull()->comment('Название города'),
            'country_id' => $this->integer()->notNull()->comment('ID страны (Ссылка на страну)'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-city-country_id',
            self::TABLE_NAME,
            'country_id'
        );

        $this->addForeignKey(
            'fk-city-country_id',
            self::TABLE_NAME,
            'country_id',
            '{{%country}}',
            'id',
            'CASCADE'
        );

        $this->addCommentOnTable(self::TABLE_NAME, 'Города');
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            'fk-city-country_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-city-country_id',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
