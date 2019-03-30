<?php

use yii\db\Migration;

/**
 * Handles adding is_archive to table `{{%task}}`.
 */
class m190330_092423_add_is_archive_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%task}}', 'is_archive', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%task}}', 'is_archive');
    }
}
