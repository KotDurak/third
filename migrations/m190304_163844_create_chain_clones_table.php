<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chain_clones}}`.
 */
class m190304_163844_create_chain_clones_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chain_clones}}', [
            'id' => $this->primaryKey(),
            'id_chain'  => $this->integer(),
            'id_task'   => $this->integer(),
        ]);

        $this->createIndex('idx_chain_main', 'chain_clones', 'id_chain');

        $this->addForeignKey(
            'fk-chain-table',
            'chain_clones',
            'id_chain',
            'chain',
            'id',
            'CASCADE',
           'CASCADE'
        );

        $this->createIndex('idx_task_main', 'chain_clones', 'id_task');

        $this->addForeignKey(
            'fk-task_stable',
            'chain_clones',
            'id_task',
            'task',
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
        $this->dropTable('{{%chain_clones}}');
    }
}
