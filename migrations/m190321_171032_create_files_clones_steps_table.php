<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files_clones_steps}}`.
 */
class m190321_171032_create_files_clones_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files_task_steps}}', [
            'id' => $this->primaryKey(),
            'id_file' => $this->integer(),
            'id_step' => $this->integer(),
            'id_task' => $this->integer()
        ]);

        $this->createIndex(
            'idx_clones_file',
            'files_task_steps',
            'id_file'
        );

        $this->addForeignKey(
            'fk-clones-files',
            'files_task_steps',
            'id_file',
            'files',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx_clones_steps_files',
            'files_task_steps',
            'id_step'
        );

        $this->addForeignKey(
            'fk-files_clones_steps-id_step',
            'files_task_steps',
            'id_step',
            'steps',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-files',
            'files_task_steps',
            'id_task'
        );

        $this->addForeignKey(
            'fk-task-step-file',
            'files_task_steps',
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
        $this->dropTable('{{%files_clones_steps}}');
    }
}
