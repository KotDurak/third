<?php

use yii\db\Migration;

/**
 * Handles adding sort to table `{{%steps}}`.
 */
class m190304_185227_add_sort_column_to_steps_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%steps}}', 'sort', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%steps}}', 'sort');
    }
}
