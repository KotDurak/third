<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190225_165725_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name'  => $this->string()->notNull(),
            'surname'   => $this->string()->notNull(),
            'password'  => $this->string(),
            'email'     => $this->string()->unique(),
            'is_root'   => $this->string(),
            'birthday'  => $this->date(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'date_create'   => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
          //  'email_confirm_token'   => $this->string()->unique()->after('email')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
