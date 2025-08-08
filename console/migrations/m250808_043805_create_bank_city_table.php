<?php

use yii\db\Migration;

class m250808_043805_create_bank_city_table extends Migration
{
    private const string TABLE_NAME = '{{%bank_city}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'bank_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-bank_city', self::TABLE_NAME, ['bank_id', 'city_id']);

        $this->createIndex(
            'idx-bank_city-bank_id',
            self::TABLE_NAME,
            'bank_id'
        );

        $this->addForeignKey(
            'fk-bank_city-bank_id',
            self::TABLE_NAME,
            'bank_id',
            '{{%bank}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-bank_city-city_id',
            self::TABLE_NAME,
            'city_id'
        );

        $this->addForeignKey(
            'fk-bank_city-city_id',
            self::TABLE_NAME,
            'city_id',
            '{{%city}}',
            'id',
            'CASCADE'
        );

        $this->addCommentOnTable(self::TABLE_NAME, 'Таблица связей Банк <=> Город');
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            'fk-bank_city-city_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-bank_city-city_id',
            self::TABLE_NAME
        );

        $this->dropForeignKey(
            'fk-bank_city-bank_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-bank_city-bank_id',
            self::TABLE_NAME
        );


        $this->dropTable(self::TABLE_NAME);
    }
}
