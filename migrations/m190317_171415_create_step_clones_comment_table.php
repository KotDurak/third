<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%step_clones_comment}}`.
 */
class m190317_171415_create_step_clones_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%step_clones_comment}}', [
            'id' => $this->primaryKey(),
            'id_step_clone' => $this->integer(),
            'id_user'   => $this->integer(),
            'comment'   => $this->text(),
            'timestamp' => $this->timestamp()
        ]);

        $this->createIndex(
            'idx_comment_user',
            'step_clones_comment',
            'id_user'
        );

        $this->addForeignKey(
            'fk-comment-user',
            'step_clones_comment',
            'id_user',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx_step_commet',
            'step_clones_comment',
            'id_step_clone'
        );

        $this->addForeignKey(
            'fk-step-comment',
            'step_clones_comment',
            'id_step_clone',
            'chain_clones_steps',
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
        $this->dropTable('{{%step_clones_comment}}');
    }
}
