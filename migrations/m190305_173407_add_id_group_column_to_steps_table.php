<?php

use yii\db\Migration;

/**
 * Handles adding id_group to table `{{%steps}}`.
 */
class m190305_173407_add_id_group_column_to_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%steps}}', 'id_group', $this->integer());

        $this->createIndex('idx-step_group', 'steps', 'id_group');

        $this->addForeignKey(
            'fk-steps-groups',
            'steps',
            'id_group',
            'groups',
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
        $this->dropColumn('{{%steps}}', 'id_group');
    }
}
