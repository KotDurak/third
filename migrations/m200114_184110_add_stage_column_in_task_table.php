<?php

use yii\db\Migration;

/**
 * Class m200114_184110_add_stage_column_in_task_table
 */
class m200114_184110_add_stage_column_in_task_table extends Migration
{
    const TABLE = '{{%task}}';
    const COLUMN = 'stage';
    const INDEX = 'idx-task-stage-column';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, self::COLUMN, $this->string());
        $this->createIndex(self::INDEX, self::TABLE, self::COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, self::COLUMN);
        $this->dropIndex(self::INDEX, self::TABLE);
    }
}
