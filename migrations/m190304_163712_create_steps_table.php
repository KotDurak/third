<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%steps}}`.
 */
class m190304_163712_create_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%steps}}', [
            'id' => $this->primaryKey(),
            'id_chain' => $this->integer(),
            'name'      => $this->string(),
            'type'      => "ENUM('table', 'attributes')"
        ]);

        $this->createIndex('idx_chain', 'steps', 'id_chain');

        $this->addForeignKey(
            'fk_chain_table',
            'steps',
            'id_chain',
            'chain',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%steps}}');
    }
}
