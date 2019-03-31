<?php

use yii\db\Migration;

/**
 * Handles adding id_archive to table `{{%task}}`.
 */
class m190331_045750_add_id_archive_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'id_archive', $this->integer());

        $this->createIndex('idx_id_archive', 'task', 'id_archive');

        $this->addForeignKey(
            'fk_task_id_archive',
            'task',
            'id_archive',
            'files',
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
        $this->dropColumn('{{%task}}', 'id_archive');
    }
}
