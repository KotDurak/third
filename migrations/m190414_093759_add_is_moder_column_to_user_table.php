<?php

use yii\db\Migration;

/**
 * Handles adding is_moder to table `{{%user}}`.
 */
class m190414_093759_add_is_moder_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_moderator', $this->integer());
        $this->addColumn('{{%user}}', 'is_ban', $this->integer());
        $this->addColumn('{{%user}}', 'rate', $this->string());
        $this->addColumn('{{%user}}', 'type_rate', $this->string());
        $this->addColumn('{{%user}}', 'about', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'is_moderator');
        $this->dropColumn('{{%user}}', 'is_ban');
        $this->dropColumn('{{%user}}', 'rate');
        $this->dropColumn('{{%user}}', 'type_rate');
        $this->dropColumn('{{%user}}', 'about');
    }
}
