<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m190318_171256_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'tmp'   => $this->string(),
            'real-name' => $this->string(),
            'name'      => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
