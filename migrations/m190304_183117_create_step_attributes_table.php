<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%step_attributes}}`.
 */
class m190304_183117_create_step_attributes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%step_attributes}}', [
            'id' => $this->primaryKey(),
            'id_step'   => $this->integer(),
            'name'      => $this->string(),
            'index' => $this->string()->unique(),
            'def_value' => $this->string()
        ]);

        $this->createIndex('index_step_atribute', 'step_attributes', 'id_step');

        $this->addForeignKey(
            'fk-key-attr-step',
            'step_attributes',
            'id_step',
            'steps',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%step_attributes}}');
    }
}
