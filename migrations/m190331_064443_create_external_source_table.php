<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%external_source}}`.
 */
class m190331_064443_create_external_source_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%external_source}}', [
            'id' => $this->primaryKey(),
            'id_step'   => $this->integer(),
            'id_task'   => $this->integer(),
            'src'       => $this->string()
        ]);

        $this->createIndex(
            'idx_external_source_step',
            'external_source',
            'id_step'
        );

        $this->addForeignKey(
            'fk-external-source-step',
            'external_source',
            'id_step',
            'steps',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-external_source-task',
            'external_source',
            'id_task'
        );

        $this->addForeignKey(
            'fk-external_source-task',
            'external_source',
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
        $this->dropTable('{{%external_source}}');
    }
}
