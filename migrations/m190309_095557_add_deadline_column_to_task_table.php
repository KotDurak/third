<?php

use yii\db\Migration;

/**
 * Handles adding deadline to table `{{%task}}`.
 */
class m190309_095557_add_deadline_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'deadline', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'deadline');
    }
}
