<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_table}}`.
 */
class m190303_131350_create_task_table_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_table}}', [
            'id' => $this->primaryKey(),
            'id_task'   => $this->integer()
        ]);

        $this->createIndex('idx_task', 'task_table', 'id_task');

        $this->addForeignKey(
            'fk-task-table',
            'task_table',
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
        $this->dropTable('{{%task_table}}');
    }
}
