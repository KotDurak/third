<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attributes_values}}`.
 */
class m190304_183143_create_attributes_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%attributes_values}}', [
            'id' => $this->primaryKey(),
            'id_attribute'  => $this->integer(),
            'id_step_clone'  => $this->integer(),
            'value'    => $this->string()
        ]);

        $this->createIndex('idx-attr-value', 'attributes_values', 'id_attribute');

        $this->addForeignKey(
            'fk-key-val-to-attr',
            'attributes_values',
            'id_attribute',
            'step_attributes',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-ste-to-clone', 'attributes_values','id_step_clone');

        $this->addForeignKey(
            'fk-steps-key-val',
            'attributes_values',
            'id_step_clone',
            'chain_clones_steps',
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
        $this->dropTable('{{%attributes_values}}');
    }
}
