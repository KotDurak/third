<?php

use yii\db\Migration;

/**
 * Class m190414_102013_add_last_visit_clolumn_to_user_table
 */
class m190414_102013_add_last_visit_clolumn_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'last_visit', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'last_visit');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190414_102013_add_last_visit_clolumn_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
