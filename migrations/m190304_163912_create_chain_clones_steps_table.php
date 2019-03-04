<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chain_clones_steps}}`.
 */
class m190304_163912_create_chain_clones_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chain_clones_steps}}', [
            'id' => $this->primaryKey(),
            'id_clone'  => $this->integer(),
            'id_step'   => $this->integer(),
            'status'    => $this->smallInteger()
        ]);

        $this->createIndex('idx_step_to_clone', 'chain_clones_steps', 'id_clone');

        $this->addForeignKey(
            'fk-clone-stpes',
            'chain_clones_steps',
            'id_clone',
            'chain_clones',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx_step_clone_step',
            'chain_clones_steps',
            'id_step'
        );

        $this->addForeignKey(
            'fk-id-step-clone',
            'chain_clones_steps',
            'id_step',
            'steps',
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
        $this->dropTable('{{%chain_clones_steps}}');
    }
}
