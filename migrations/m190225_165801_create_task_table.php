<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m190225_165801_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'name'  => $this->string(),
            'description'   => $this->text(),
            'status'        => $this->integer(),
            'id_user'   => $this->integer(),
            'id_manager'    => $this->integer(),
        ]);

        $this->createIndex(
          'idx_user',
          'task',
          'id_user'
        );

        $this->addForeignKey(
            'fk_user2',
            'task',
            'id_user',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'fk_manafer2',
            'task',
            'id_manager'
        );

        $this->addForeignKey(
            'fk_manager2',
            'task',
            'id_manager',
            'user',
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
        $this->dropTable('{{%task}}');
    }
}
