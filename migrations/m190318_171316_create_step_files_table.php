<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%step_files}}`.
 */
class m190318_171316_create_step_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%step_files}}', [
            'id' => $this->primaryKey(),
            'id_file'   => $this->integer(),
            'id_step'   => $this->integer()
        ]);

        $this->createIndex(
            'step_file_file',
            'step_files',
            'id_file'
        );

        $this->addForeignKey(
            'fk-ste-file-file',
            'step_files',
            'id_file',
            'files',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx_step_file_step',
            'step_files',
            'id_step'
        );

        $this->addForeignKey(
            'fk-step-files-step',
            'step_files',
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
        $this->dropTable('{{%step_files}}');
    }
}
