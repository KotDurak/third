<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_table_rows}}`.
 */
class m190303_132242_create_task_table_rows_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_table_rows}}', [
            'id' => $this->primaryKey(),
            'id_table'  => $this->integer(),
            'base'      => $this->string(),
            'phrase'    => $this->string(),
            'base'      => $this->integer(),
            'frequence_e'  => $this->integer(),
            'frequence_f'   => $this->integer()

        ]);

        $this->createIndex('idx_my_table', 'task_table_rows', 'id_table');

        $this->addForeignKey(
            'fk_my_table',
            'task_table_rows',
            'id_table',
            'task_table',
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
        $this->dropTable('{{%task_table_rows}}');
    }
}
