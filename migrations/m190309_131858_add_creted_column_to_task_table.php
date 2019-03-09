<?php

use yii\db\Migration;

/**
 * Handles adding creted to table `{{%task}}`.
 */
class m190309_131858_add_creted_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'created', $this->timestamp());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'created');
    }
}
