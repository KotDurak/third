<?php

use yii\db\Migration;

/**
 * Handles adding id_user to table `{{%chain_clones_steps}}`.
 */
class m190304_175933_add_id_user_column_to_chain_clones_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%chain_clones_steps}}', 'id_user', $this->integer());

        $this->createIndex('idx_user_to_task', 'chain_clones_steps', 'id_user');

        $this->addForeignKey(
            'fk_user_to_step',
            'chain_clones_steps',
            'id_user',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%chain_clones_steps}}', 'id_user');
    }
}
