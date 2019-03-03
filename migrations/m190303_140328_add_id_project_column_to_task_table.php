<?php

use yii\db\Migration;

/**
 * Handles adding id_project to table `{{%task}}`.
 */
class m190303_140328_add_id_project_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'id_project', $this->integer());
        $this->createIndex('idx_project_task', 'task', 'id_project');
        $this->addForeignKey(
            'fk-project_task',
            'task',
            'id_project',
            'project',
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
        $this->dropColumn('{{%task}}', 'id_project');
    }
}
