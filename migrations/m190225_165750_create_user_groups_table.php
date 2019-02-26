<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_groups}}`.
 */
class m190225_165750_create_user_groups_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_groups}}', [
            'id' => $this->primaryKey(),
            'id_user'   => $this->integer(),
            'id_group'  => $this->integer()
        ]);

        $this->createIndex(
          'idx_user',
          'user_groups',
          'id_user'
        );

        $this->addForeignKey(
          'fk_user',
          'user_groups',
          'id_user',
          'user',
          'id',
          'CASCADE',
          'CASCADE'
        );

        $this->createIndex(
          'idx_group',
          'user_groups',
          'id_group'
        );

        $this->addForeignKey(
            'fk_group',
            'user_groups',
            'id_group',
            'groups',
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
        $this->dropTable('{{%user_groups}}');
    }
}
