<?php

use yii\db\Migration;

class m250808_044437_create_bank_service_table extends Migration
{
    private const string TABLE_NAME = '{{%bank_service}}';

    public function safeUp(): void
    {
        $this->createTable(self::TABLE_NAME, [
            'bank_id'    => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-bank_service', self::TABLE_NAME, ['bank_id', 'service_id']);

        $this->createIndex(
            'idx-bank_service-bank_id',
            self::TABLE_NAME,
            'bank_id'
        );

        $this->addForeignKey(
            'fk-bank_service-bank_id',
            self::TABLE_NAME,
            'bank_id',
            '{{%bank}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-bank_service-service_id',
            self::TABLE_NAME,
            'service_id'
        );

        $this->addForeignKey(
            'fk-bank_service-service_id',
            self::TABLE_NAME,
            'service_id',
            '{{%service}}',
            'id',
            'CASCADE'
        );

        $this->addCommentOnTable(self::TABLE_NAME, 'Таблица связей Банк <=> Услуги');
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            'fk-bank_service-service_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-bank_service-service_id',
            self::TABLE_NAME
        );

        $this->dropForeignKey(
            'fk-bank_service-bank_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-bank_service-bank_id',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
