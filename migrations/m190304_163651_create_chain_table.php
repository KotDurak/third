<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chain}}`.
 */
class m190304_163651_create_chain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chain}}', [
            'id' => $this->primaryKey(),
            'name'  => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chain}}');
    }
}
